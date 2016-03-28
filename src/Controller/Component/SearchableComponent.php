<?php
namespace Search\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Plugin;
use \FileSystemIterator;
use \RuntimeException;

/**
 * Searchable component
 */
class SearchableComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Get all the tables with Searchable fields functionallity.
     *
     * @return array
     */
    protected function _getSearchableTables()
    {
        $tables = $this->_getAllTables();
        foreach ($tables as $container => $containerTables) {
            foreach ($containerTables as $key => $table) {
                if ($container === 'app') {
                    $this->loadModel($table);
                } else {
                    $this->loadModel($container . '.' . $table);
                }
                if (!method_exists($this->{$table}, 'getSearchableFields')) {
                    unset($tables[$container][$key]);
                }
            }
        }

        return array_filter($tables);
    }

    /**
     * Get all the tables from application and plugins.
     *
     * @return [type]                [description]
     */
    protected function _getAllTables()
    {
        $result['app'] = $this->_getTables(APP);
        $plugins = Plugin::loaded();
        foreach ($plugins as $plugin) {
            $result[$plugin] = $this->_getTables(Plugin::path($plugin));
        }

        return array_filter($result);
    }

    /**
     * Just get the tables from the the given path.
     *
     * @throws RuntimeException When path is not provided.
     * @param  string $path The directory which contains the tables.
     * @return array  Either empty array or the found tables.
     */
    protected function _getTables($path = '')
    {
        $result = [];

        if (empty($path)) {
            throw new RuntimeException('Please provide path of Tables to proceed.');
        }

        $path = $path . 'src' . DS . 'Model' . DS . 'Table';
        if (!file_exists($path)) {
            return $result;
        }

        $it = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
        foreach ($it as $file) {
            //Exclude anything without the table prefix.
            if (!strpos($file->getFilename(), 'Table.php')) {
                continue;
            }
            $table = $file->getBasename('Table.php');
            array_push($result, $table);
        }

        return $result;
    }
}
