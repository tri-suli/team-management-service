<?php

namespace Tests\Feature\Rules;

use App\Rules\PlayerPositionRule;
use Tests\TestCase;

class PlayerPositionRuleTest extends TestCase
{
    /**
     * Test positive result
     *
     * @return void
     */
    public function test_rule_pass()
    {
        $rule = new PlayerPositionRule();

        $result = $rule->passes('position', 'defender');

        $this->assertTrue($result);
    }

    /**
     * Test negative result
     *
     * @return void
     */
    public function test_rule_did_not_pass()
    {
        $rule = new PlayerPositionRule();

        $result = $rule->passes('position', 'coach');

        $this->assertFalse($result);
        $this->assertStringContainsString('position', $rule->message());
    }
}
