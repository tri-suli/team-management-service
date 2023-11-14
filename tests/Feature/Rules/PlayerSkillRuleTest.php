<?php

namespace Feature\Rules;

use App\Rules\PlayerSkillRule;
use Tests\TestCase;

class PlayerSkillRuleTest extends TestCase
{
    /**
     * Test positive result
     *
     * @return void
     */
    public function test_rule_pass()
    {
        $rule = new PlayerSkillRule();

        $result = $rule->passes('skill', 'attack');

        $this->assertTrue($result);
    }

    /**
     * Test negative result
     *
     * @return void
     */
    public function test_rule_did_not_pass()
    {
        $rule = new PlayerSkillRule();

        $result = $rule->passes('skill', 'throwing');

        $this->assertFalse($result);
        $this->assertStringContainsString('skill', $rule->message());
    }
}
