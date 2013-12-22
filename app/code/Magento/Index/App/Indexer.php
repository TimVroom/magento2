<?php
/**
 * Indexer application
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Index\App;

use Magento\AppInterface;

class Indexer implements AppInterface
{
    /**
     * Report directory
     *
     * @var string
     */
    protected $_reportDir;

    /**
     * @var \Magento\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magento\Index\Model\IndexerFactory
     */
    protected $_indexerFactory;

    /**
     * @param string $reportDir
     * @param \Magento\Filesystem $filesystem
     * @param \Magento\Index\Model\IndexerFactory $indexerFactory
     */
    public function __construct(
        $reportDir,
        \Magento\Filesystem $filesystem,
        \Magento\Index\Model\IndexerFactory $indexerFactory
    ) {
        $this->_reportDir = $reportDir;
        $this->_filesystem = $filesystem;
        $this->_indexerFactory = $indexerFactory;
    }

    /**
     * Run application
     *
     * @return int
     */
    public function execute()
    {
        /* Clean reports */
        $directory = $this->_filesystem->getDirectoryWrite(\Magento\Filesystem::ROOT);
        $path = $directory->getRelativePath($this->_reportDir);
        if ($directory->isExist($path)) {
            $directory->delete($path);
        }

        /* Run all indexer processes */
        /** @var $indexer \Magento\Index\Model\Indexer */
        $indexer = $this->_indexerFactory->create();
        /** @var $process \Magento\Index\Model\Process */
        foreach ($indexer->getProcessesCollection() as $process) {
            if ($process->getIndexer()->isVisible()) {
                $process->reindexEverything();
            }
        }
        return 0;
    }
}

