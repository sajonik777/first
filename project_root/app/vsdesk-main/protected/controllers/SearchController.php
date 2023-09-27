<?php


/**
 * Class SearchController
 */
class SearchController extends Controller
{
    public $layout = '//layouts/design3';

    public function actionIndex($q)
    {

        $ticketsResults = Request::model()->searchSame($q);
        $knowledgeResults = Knowledge::model()->searchSame($q);

        $results = $knowledgeResults || $ticketsResults ? array() : null;

        if ($ticketsResults) {
            array_push($results, array(
                'id' => 'tickets',
                'name' => 'Заявки',
                'urlMask' => '/request/',
                'data' => $ticketsResults
            ));
        }

        if ($knowledgeResults) {
            array_push($results, array(
                'id' => 'knowledge',
                'name' => 'База знаний',
                'urlMask' => '/knowledge/module/view/id/',
                'data' => $knowledgeResults
            ));
        }

        $this->render('view', [
            'query' => $q,
            'results' => $results
        ]);
    }
}