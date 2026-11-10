<?php declare(strict_types=1);

use Model\Skills;
use PHPUnit\Framework\TestCase;

final class SkillsTest extends TestCase
{
	public function testThereAreSixSkills(): void
	{
		$this->assertCount(6, Skills::skills());
	}

	public function testEverySkillHasNameAndInfluence(): void
	{
		foreach (Skills::skills() as $id => $skill) {
			$this->assertNotEmpty($skill['name'], "skill $id name");
			$this->assertIsArray(($skill['influence'])(1), "skill $id influence");
		}
	}
}
