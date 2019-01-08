<?php

namespace Hoogi91\Charts\Domain\Model;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class ChartData
 * @package Hoogi91\Charts\Domain\Model
 */
abstract class ChartData extends AbstractEntity
{
    const TYPE_PLAIN = 0;
    const TYPE_SPREADSHEET = 1;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $labels;

    /**
     * @var string
     */
    protected $datasets;

    /**
     * @var string
     */
    protected $datasetsLabels;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getType()
    {
        if (!in_array($this->type, $this->getAllowedTypes())) {
            return static::TYPE_PLAIN;
        }
        return (int)$this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type = self::TYPE_PLAIN)
    {
        if (in_array($type, $this->getAllowedTypes())) {
            $this->type = $type;
        }
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        // only get first row of labels and ignore multiple column/row selections
        $labels = $this->extractLabelList($this->labels);
        return array_shift($labels);
    }

    /**
     * @param string $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    /**
     * @return array
     */
    public function getDatasets()
    {
        return $this->extractDatasetList($this->datasets);
    }

    /**
     * @param string $datasets
     */
    public function setDatasets($datasets)
    {
        $this->datasets = $datasets;
    }

    /**
     * @return array
     */
    public function getDatasetsLabels()
    {
        // only get single row of labels => in javascript this should be mapped together with datasets
        $labels = $this->extractLabelList($this->datasetsLabels);
        return array_shift($labels);
    }

    /**
     * @param string $datasetsLabels
     */
    public function setDatasetsLabels($datasetsLabels)
    {
        $this->datasetsLabels = $datasetsLabels;
    }

    /**
     * @return array
     */
    protected function getAllowedTypes()
    {
        $allowedTypes = [static::TYPE_PLAIN];
        if (ExtensionManagementUtility::isLoaded('spreadsheets')) {
            // only allow spreadsheet type if required extension is loaded
            $allowedTypes[] = static::TYPE_SPREADSHEET;
        }
        return $allowedTypes;
    }

    /**
     * @param string $labelData
     *
     * @return array
     */
    abstract protected function extractLabelList($labelData);

    /**
     * @param string $datasetData
     *
     * @return array
     */
    abstract protected function extractDatasetList($datasetData);
}
