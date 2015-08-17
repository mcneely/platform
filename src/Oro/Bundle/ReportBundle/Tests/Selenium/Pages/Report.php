<?php

namespace Oro\Bundle\ReportBundle\Tests\Selenium\Pages;

use Oro\Bundle\TestFrameworkBundle\Pages\AbstractPageEntity;

/**
 * Class Report
 *
 * @package Oro\Bundle\ReportBundle\Tests\Selenium\Pages
 * @method Reports openReports(string $bundlePath)
 * @method Report openReport(string $bundlePath)
 */
class Report extends AbstractPageEntity
{
    protected $organization = '//select[@data-ftid="oro_report_form_owner"]';

    public function setName($name)
    {
        $this->test->byXPath("//input[@data-ftid='oro_report_form_name']")->value($name);

        return $this;
    }

    public function setDescription($description)
    {
        $this->test->byXPath("//input[@data-ftid='oro_report_form_description']")->value($description);
        return $this;
    }

    public function setEntity($entity)
    {
        $this->test->byXPath("//div[starts-with(@id,'s2id_oro_report_form_entity')]/a")->click();
        $this->waitForAjax();
        $this->test->byXPath("//div[@id='select2-drop']/div/input")->value($entity);
        $this->waitForAjax();
        $this->assertElementPresent(
            "//div[@id='select2-drop']//div[contains(., '{$entity}')]",
            "Entity autocomplete doesn't return search value"
        );
        $this->test->byXPath("//div[@id='select2-drop']//div[contains(., '{$entity}')]")->click();
        $this->waitPageToLoad();
        $this->waitForAjax();
        return $this;
    }

    public function setType($type)
    {
        $this->test
            ->select($this->test->byXPath("//select[@data-ftid='oro_report_form_type']"))
            ->selectOptionByLabel($type);

        return $this;
    }

    /**
     * @param string|array $columns
     * @return $this
     */
    public function addColumn($columns)
    {
        if (!is_array($columns)) {
            $columns = [$columns];
        }
        foreach ($columns as $column) {
            $this->test->byXPath("//div[starts-with(@id,'s2id_oro_report_form_column_name')]/a")->click();
            $this->waitForAjax();
            $this->test->byXPath("//div[@id='select2-drop']/div/input")->value($column);
            $this->waitForAjax();
            $this->assertElementPresent(
                "//div[@id='select2-drop']//div[contains(., '{$column}')]",
                "Entity column autocomplete doesn't return search value"
            );
            $this->test->byXPath("//div[@id='select2-drop']//div[contains(., '{$column}')]")->click();
            $this->test->byXPath("//a[@title='Add']")->click();
            $this->waitForAjax();
        }

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function addFieldCondition($column, $value)
    {
        $element = $this->test->byXPath("//li[@data-criteria='condition-item']");
        $element1 = $this->test->byXPath("//div[@id='oro_report-condition-builder']/ul");
        $this->test->moveto($element);
        $this->test->buttondown();
        $this->test->moveto($element1);
        $this->test->buttonup();

        $this->test->byXPath("(//div[@class='condition-item'])[1]//a")->click();
        $this->waitForAjax();
        $this->test->byXPath("//div[@id='select2-drop']/div/input")->value($column);
        $this->waitForAjax();
        $this->assertElementPresent(
            "//div[@id='select2-drop']//div[contains(., '{$column}')]",
            "Condition autocomplete doesn't return search value"
        );
        $this->test->byXPath("//div[@id='select2-drop']//div[contains(., '{$column}')]")->click();
        $this->test->byXPath("(//div[@class='condition-item'])[1]//input[@name='value']")->value($value);

        return $this;
    }
}
