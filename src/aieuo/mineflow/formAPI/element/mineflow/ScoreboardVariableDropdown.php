<?php

namespace aieuo\mineflow\formAPI\element\mineflow;

use aieuo\mineflow\variable\DummyVariable;

class ScoreboardVariableDropdown extends VariableDropdown {

    protected $variableType = DummyVariable::SCOREBOARD;

    public function __construct(array $variables = [], string $default = "") {
        parent::__construct("@flowItem.form.target.scoreboard", $variables, [DummyVariable::SCOREBOARD], $default);
    }
}