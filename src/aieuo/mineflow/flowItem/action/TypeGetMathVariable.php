<?php

namespace aieuo\mineflow\flowItem\action;

use aieuo\mineflow\formAPI\CustomForm;
use aieuo\mineflow\formAPI\element\Input;
use aieuo\mineflow\formAPI\element\Label;
use aieuo\mineflow\formAPI\element\Toggle;
use aieuo\mineflow\formAPI\Form;
use aieuo\mineflow\recipe\Recipe;
use aieuo\mineflow\utils\Categories;
use aieuo\mineflow\utils\Language;

abstract class TypeGetMathVariable extends Action {

    protected $detailDefaultReplace = ["result"];

    protected $category = Categories::CATEGORY_ACTION_CALCULATION;

    protected $targetRequired = Recipe::TARGET_REQUIRED_NONE;
    protected $returnValueType = self::RETURN_VARIABLE_NUMBER;

    /** @var string */
    protected $resultName = "result";

    public function __construct(?string $result = null) {
        $this->resultName = $result ?? $this->resultName;
    }

    public function setResultName(string $name): self {
        $this->resultName = $name;
        return $this;
    }

    public function getResultName(): string {
        return $this->resultName;
    }

    public function isDataValid(): bool {
        return $this->getResultName() !== "";
    }

    public function getDetail(): string {
        if (!$this->isDataValid()) return $this->getName();
        return Language::get($this->detail, [$this->getResultName()]);
    }

    public function getEditForm(array $default = [], array $errors = []): Form {
        return (new CustomForm($this->getName()))
            ->setContents([
                new Label($this->getDescription()),
                new Input("@action.calculate.form.result", Language::get("form.example", ["result"]), $default[1] ?? $this->getResultName()),
                new Toggle("@form.cancelAndBack")
            ])->addErrors($errors);
    }

    public function parseFromFormData(array $data): array {
        $errors = [];
        if ($data[1] === "") {
            $errors[] = ["@form.insufficient", 1];
        }
        return ["status" => empty($errors), "contents" => [$data[1]], "cancel" => $data[2], "errors" => $errors];
    }

    public function loadSaveData(array $content): Action {
        if (isset($content[0])) $this->setResultName($content[0]);
        return $this;
    }

    public function serializeContents(): array {
        return [$this->getResultName()];
    }
}