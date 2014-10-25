<?php
	/**
	 * this class is for advanced search methods
	 *
	 * @author stretch
	 */
	class search extends N8_Controller {

		public function __construct() {
			parent::__construct();

            $this->load->view('Search');
            $this->view = new SearchVW();

//            try {
//                $this->generateCategoriesNav();
//            } catch(Exception $e) {
//                $this->logMessage( $e->getMessage(), N8_Error::ERROR );
//            }
		}

        /**
         * generate the advanced search form
         *
         * @param string $cache_key
         */
		public function advanced($cache_key = null) {
            $cache_array = false;

            try {
                if($cache_key) {
                    $cache_util = new CacheUtil();
                    $cache_array = $cache_util->fetchCache($cache_key);
                }

                if(!$cache_array) {
                    $cache_array = array();
                    $cache_array['event_title']['value'] = 'Event Title';
                    $cache_array['event_title']['error'] = null;
                    $cache_array['city']['value'] = 'City';
                    $cache_array['city']['error'] = null;
                    $cache_array['state']['value'] = 'State';
                    $cache_array['state']['error'] = null;
                    $cache_array['zip']['value'] = 'Zip';
                    $cache_array['zip']['error'] = null;
                    $cache_array['from_date']['value'] = 'From Date';
                    $cache_array['from_date']['error'] = null;
                    $cache_array['to_date']['value'] = 'To Date';
                    $cache_array['to_date']['error'] = null;
                }

				$form = new Form();
				$form->setAction('map/search');
				$form->addHiddenInput('search_type', 'advanced_search');
                $form->setName('advanced_search');

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setName('event_title')
                      ->setId('event_title')
                      ->setValue( $cache_array[$field->getName()]['value'] )
                      ->setClass('toggle_text form_text')
                      ->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setName( "city" )
                      ->setId('city')
                      ->setValue( $cache_array[$field->getName()]['value'] )
				      ->setClass('toggle_text form_text')
                      ->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setName( "state" )
                      ->setId('state')
                      ->setValue( $cache_array[$field->getName()]['value'] )
				      ->setClass('toggle_text form_text')
                      ->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setName( "zip" )
                      ->setId('zip')
				      ->setValue( $cache_array[$field->getName()]['value'] )
				      ->setMaxLength(5)
				      ->setSize(5)
				      ->setClass('toggle_text form_text')
                      ->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setName( "from_date" )
                      ->setId('from_date')
				      ->setSize(10)
				      ->setValue( $cache_array[$field->getName()]['value'] )
				      ->setClass('toggle_text form_text hasDatePicker')
                      ->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setName( "to_date" )
                      ->setId('to_date')
				      ->setSize(10)
				      ->setValue( $cache_array[$field->getName()]['value'] )
				      ->setClass('toggle_text form_text hasDatePicker')
                      ->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
				$field->setId( "search_submit" );
				$field->setContent( 'Search' );

				$form->addField( $field );

				$this->view->setErrors( $this->getErrors() );
				$this->view->setSearchForm( $form );
				$this->view->setPageId('advanced_search');

				$this->view->renderView();
			} catch(Exception $e) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}
	}

?>
