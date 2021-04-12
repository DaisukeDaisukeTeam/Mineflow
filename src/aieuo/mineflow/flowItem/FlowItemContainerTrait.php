<?php

namespace aieuo\mineflow\flowItem;

trait FlowItemContainerTrait {

    /** @var FlowItem[][] */
    private $items = [];

    /**
     * @param FlowItem $action
     * @param string $name
     */
    public function addItem(FlowItem $action, string $name): void {
        $this->items[$name][] = $action;
    }

    /**
     * @param array $actions
     * @param string $name
     */
    public function setItems(array $actions, string $name): void {
        $this->items[$name] = $actions;
    }

    /**
     * @param int $index
     * @param FlowItem $action
     * @param string $name
     */
    public function pushItem(int $index, FlowItem $action, string $name): void {
        array_splice($this->items[$name], $index, 0, [$action]);
    }

    /**
     * @param int $index
     * @param string $name
     * @return FlowItem|null
     */
    public function getItem(int $index, string $name): ?FlowItem {
        return $this->items[$name][$index] ?? null;
    }

    /**
     * @param int $index
     * @param string $name
     */
    public function removeItem(int $index, string $name): void {
        unset($this->items[$name][$index]);
        $this->items[$name] = array_merge($this->items[$name]);
    }

    /**
     * @param string $name
     * @return FlowItem[]
     */
    public function getItems(string $name): array {
        return $this->items[$name] ?? [];
    }

    /**
     * @return FlowItem[]
     */
    public function getActions(): array {
        return $this->getItems(self::ACTION);
    }

    /**
     * @return FlowItem[]
     */
    public function getConditions(): array {
        return $this->getItems(self::CONDITION);
    }

    public function getAddingVariablesBefore(FlowItem $flowItem, array $containers, string $type): array {
        $variables = [];

        /** @var FlowItem|FlowItemContainer $target */
        $target = array_shift($containers);
        if ($target !== null) {
            $variables = array_merge($target->getAddingVariables(), $target->getAddingVariablesBefore($flowItem, $containers, $type));
        }
        $target = $target ?? $flowItem;

        $variablesMerge = [];
        foreach ($this->getItems($type) as $item) {
            if ($item === $target) break;
            $variablesMerge[] = $item->getAddingVariables();
        }
        $variables = array_merge($variables, ...$variablesMerge);
        return $variables;
    }
}