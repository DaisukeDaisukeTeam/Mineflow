<?php

namespace aieuo\mineflow\flowItem\action;

use aieuo\mineflow\flowItem\base\ItemFlowItem;
use aieuo\mineflow\flowItem\base\ItemFlowItemTrait;
use aieuo\mineflow\flowItem\FlowItem;
use aieuo\mineflow\flowItem\FlowItemExecutor;
use aieuo\mineflow\formAPI\element\mineflow\ExampleInput;
use aieuo\mineflow\formAPI\element\mineflow\ItemVariableDropdown;
use aieuo\mineflow\utils\Category;
use aieuo\mineflow\utils\Language;
use aieuo\mineflow\variable\DummyVariable;

class SetItemName extends FlowItem implements ItemFlowItem {
    use ItemFlowItemTrait;

    protected $id = self::SET_ITEM_NAME;

    protected $name = "action.setItemName.name";
    protected $detail = "action.setItemName.detail";
    protected $detailDefaultReplace = ["item", "name"];

    protected $category = Category::ITEM;
    protected $returnValueType = self::RETURN_VARIABLE_NAME;

    /** @var string */
    private $itemName;

    public function __construct(string $item = "", string $itemName = "") {
        $this->setItemVariableName($item);
        $this->itemName = $itemName;
    }

    public function setItemName(string $itemName): void {
        $this->itemName = $itemName;
    }

    public function getItemName(): string {
        return $this->itemName;
    }

    public function isDataValid(): bool {
        return $this->getItemVariableName() !== "";
    }

    public function getDetail(): string {
        if (!$this->isDataValid()) return $this->getName();
        return Language::get($this->detail, [$this->getItemVariableName(), $this->getItemName()]);
    }

    public function execute(FlowItemExecutor $source): \Generator {
        $this->throwIfCannotExecute();

        $name = $source->replaceVariables($this->getItemName());

        $item = $this->getItem($source);

        $item->setCustomName($name);
        yield true;
        return $this->getItemVariableName();
    }

    public function getEditFormElements(array $variables): array {
        return [
            new ItemVariableDropdown($variables, $this->getItemVariableName()),
            new ExampleInput("@action.createItemVariable.form.name", "aieuo", $this->getItemName(), true),
        ];
    }

    public function loadSaveData(array $content): FlowItem {
        $this->setItemVariableName($content[0]);
        $this->setItemName($content[1]);
        return $this;
    }

    public function serializeContents(): array {
        return [$this->getItemVariableName(), $this->getItemName()];
    }
}
