<?php
namespace Search\Event\View;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Request;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class DashboardViewMenuListener implements EventListenerInterface
{
    /**
     * Menu element name
     */
    const MENU_ELEMENT = 'Menu.menu';

    /**
     * Implemented Events
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Search.Dashboards.View.View.Menu.Top' => 'getViewMenuTop'
        ];
    }

    /**
     * Method that adds elements to view View top menu.
     *
     * @param  Cake\Event\Event     $event   Event object
     * @param  Cake\Network\Request $request Request object
     * @param  Cake\ORM\Entity      $entity  Entity object
     * @return void
     */
    public function getViewMenuTop(Event $event, Request $request, Entity $entity)
    {
        $controllerName = $request->controller;
        if (!empty($request->plugin)) {
            $controllerName = $request->plugin . '.' . $controllerName;
        }

        $model = TableRegistry::get($controllerName);

        $displayField = $model->displayField();

        $urlEdit = [
            'plugin' => $request->plugin,
            'controller' => $request->controller,
            'action' => 'edit',
            $entity->id
        ];
        $btnEdit = ' ' . $event->subject()->Html->link(
            '',
            $urlEdit,
            ['title' => __('Edit'), 'class' => 'btn btn-default glyphicon glyphicon-pencil']
        );

        $urlDel = [
            'plugin' => $request->plugin,
            'controller' => $request->controller,
            'action' => 'delete',
            $entity->id
        ];
        $btnDel = ' ' . $event->subject()->Form->postLink(
            '',
            $urlDel,
            [
                'confirm' => __('Are you sure you want to delete {0}?', $entity->{$displayField}),
                'title' => __('Delete'),
                'class' => 'btn btn-default glyphicon glyphicon-trash'
            ]
        );

        $menu = [
            [
                'label' => $btnEdit,
                'url' => $urlEdit,
                'capabilities' => 'fromUrl'
            ],
            [
                'label' => $btnDel,
                'url' => $urlDel,
                'capabilities' => 'fromUrl'
            ]
        ];

        if ($event->subject()->elementExists(static::MENU_ELEMENT)) {
            $event->result .= $event->subject()->element(static::MENU_ELEMENT, ['menu' => $menu, 'renderAs' => 'provided']);
        } else {
            $event->result .= $btnEdit . $btnDel;
        }
    }
}
