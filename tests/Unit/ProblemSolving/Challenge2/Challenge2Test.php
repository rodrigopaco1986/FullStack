<?php

namespace Tests\Unit;

use Tests\TestCase;

class Challenge2Test extends TestCase
{
    public function test_case_1()
    {
        $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(6, 6, 6);
        $this->assertEquals($value, 18);
    }

      public function test_case_2()
      {
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(5, 5, 5);
          $this->assertEquals($value, 15);
      }

      public function test_case_3()
      {
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(1, 2, 3);
          $this->assertEquals($value, 3);
      }

      public function test_case_4()
      {
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(1, 2, 1);
          $this->assertEquals($value, 2);
      }

      public function test_case_5()
      {
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(3, 6, 3);
          $this->assertEquals($value, 6);
      }

      public function test_case_6()
      {
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(6, 5, 4);
          $this->assertEquals($value, 6);
      }

      public function test_case_7()
      {
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(4, 5, 6);
          $this->assertEquals($value, 6);
      }

      public function test_case_8()
      {
          $this->expectException(\Exception::class);
          $this->expectExceptionMessage('Dice out of number range');
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(7, 6, 5);
      }

      public function test_case_9()
      {
          $this->expectException(\Exception::class);
          $this->expectExceptionMessage('Dice out of number range');
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(0, 6, 5);
      }

      public function test_case_10()
      {
          $this->expectException(\Exception::class);
          $this->expectExceptionMessage('Dice out of number range');
          $value = \Tests\Unit\ProblemSolving\Challenge2\Challenge2::diceFacesCalculator(-1, 2, 3);
      }
}
