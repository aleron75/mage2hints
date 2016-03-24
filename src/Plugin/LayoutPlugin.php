<?php

namespace Aleron75\Mage2Hints\Plugin;

use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Layout\Element;
use Magento\Framework\View\LayoutInterface;

class LayoutPlugin
{
    /** @var  \Magento\Framework\App\Request\Http */
    protected $request;

    /**
     * Used to define output sorting.
     */
    private $attributes = ['name', 'alias', 'parent_name', 'type', 'template', 'template_path'];

    function __construct(
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
    }

    public function aroundRenderNonCachedElement(
        LayoutInterface $subject,
        callable $proceed,
        $name
    ) {
        if (!$this->request->getParam('hints')) {
            return $proceed($name);
        }

        $attributes = [
            'name' => 'root',
        ];

        $alias = $subject->getElementAlias($name);
        if ($alias) {
            $attributes['alias'] = $alias;
        }

        $parentName = $subject->getParentName($name);
        if ($parentName) {
            $attributes['parent_name'] = $parentName;
        }

        if ($alias && $parentName) {
            $name = $subject->getChildName($parentName, $alias);
            if ($name) {
                $attributes['name'] = $name;

                /** @var BlockInterface $block */
                $block = $subject->getBlock($name);
                if ($block) {
                    $attributes['type'] = get_class($block);
                    $attributes['template'] = $block->getTemplate();
                    $attributes['template_path'] = substr(str_replace(BP, '', $block->getTemplateFile()), 1);
                }
            }
        }

        $viewElementType = $this->getViewElementType($subject, $name);

        $html = $proceed($name);

        $handles = '';
        if ($name == 'root') {
            $handles = sprintf(
                '<!-- [LAYOUT_HANDLES] %s [/LAYOUT_HANDLES] -->%s',
                implode(' - ', $subject->getUpdate()->getHandles()),
                PHP_EOL
            );
        }

        return sprintf(
            '%s<!-- [%s %s] -->%s%s%s<!-- [/%s name="%s"] -->%s',
            $handles,
            $viewElementType,
            $this->buildAttributesString($attributes),
            PHP_EOL,
            $html,
            PHP_EOL,
            $viewElementType,
            $name,
            PHP_EOL
        );
    }

    protected function getViewElementType(LayoutInterface $layout, $name)
    {
        return $layout->isUiComponent($name)
            ? strtoupper(Element::TYPE_UI_COMPONENT)
            : $layout->isBlock($name)
                ? strtoupper(Element::TYPE_BLOCK)
                : strtoupper(Element::TYPE_CONTAINER)
        ;
    }

    /**
     * @param $attributes
     * @return mixed
     */
    private function buildAttributesString($attributes)
    {
        $attributes = array_reduce(
            $this->attributes,
            function ($carry, $key) use ($attributes) {
                if (!isset($attributes[$key])) {
                    return $carry;
                }
                return $carry . ' ' . $key . '="' . $attributes[$key] . '"';
            },
            ''
        );
        return $attributes;
    }
}