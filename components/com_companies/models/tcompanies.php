<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Companies
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Companies records.
 *
 * @since  1.6
 */
class CompaniesModelTcompanies extends JModelList
{


	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('`#__companies_` AS a');
		$query->where('a.state=1');
		$query->select('categories_2744748.title AS category_title, categories_2744748.id AS category_id');
		$query->join('LEFT', '#__categories AS categories_2744748 ON categories_2744748.id = a.category');
		$orderCol = 'ordering';
		$orderDirn = 'ASC';
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{

		$db    = $this->getDbo();
		$this->setState('list.limit', 0);
		
		$items = parent::getItems();

		$sortedItems = array();
		
		foreach ($items as $item)
		{

			if (isset($item->category_id))
			{
				
				if(!isset($sortedItems[$item->category_id])){
					$db->setQuery("SELECT cat.* FROM #__categories cat WHERE cat.id='$item->category_id'");
					$category = $db->loadObject();
					
					$sortedItems[$item->category_id] = array();
					$sortedItems[$item->category_id]['title'] = $category->title;
					$sortedItems[$item->category_id]['description'] = $category->description;
					$sortedItems[$item->category_id]['items'] = array();
				}

				$sortedItems[$item->category_id]['items'][] = $item;

			}
		}

		return $sortedItems;
	}
}
