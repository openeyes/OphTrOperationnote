<?php

class m140513_101425_add_trabectome_element extends OEMigration
{
	public function up()
	{
		$event_type_id = $this->insertOEEventType( 'Operation Note', 'OphTrOperationnote', 'Ci');
		$et_ids = $this->insertOEElementType(array('Element_OphTrOperationnote_Trabectome' =>
						array(
								'name' => 'Trabectome' ,
								'parent_element_type_id' => 'Element_OphTrOperationnote_ProcedureList',
								'display_order' => 20,
								'required' => false
						)), $event_type_id);
		$element_type_id = $et_ids[0];

		$proc = $this->dbConnection->createCommand()->select("id")->from("proc")->where("snomed_code = :code",array(":code" => "11000163100"))->queryRow();

		if ($proc) {
			$this->insert('ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type_id));
		}
		else {
			echo "***** WARNING *****\nCreating Trabectome element, but no procedure found to associate with the the element\n";
		}

		$this->createTable('ophtroperationnote_trabectome_power', array(
						'id' => 'pk',
						'name' => 'string NOT NULL',
						'active' => 'boolean NOT NULL DEFAULT true',
						'display_order' => 'integer NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned DEFAULT 1',
						'last_modified_date' => "datetime DEFAULT '1900-01-01 00:00:00'",
						'created_user_id' => 'int(10) unsigned  DEFAULT 1',
						'created_date' => "datetime DEFAULT '1900-01-01 00:00:00'"
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('ophtroperationnote_trabectome_power_lmui_fk',
				'ophtroperationnote_trabectome_power',
				'last_modified_user_id', 'user', 'id');
		$this->addForeignKey('ophtroperationnote_trabectome_power_cui_fk',
				'ophtroperationnote_trabectome_power',
				'created_user_id', 'user', 'id');

		$this->createTable('ophtroperationnote_trabectome_complication', array(
						'id' => 'pk',
						'name' => 'string NOT NULL',
						'active' => 'boolean NOT NULL DEFAULT true',
						'other' => 'boolean NOT NULL DEFAULT false',
						'display_order' => 'integer NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned DEFAULT 1',
						'last_modified_date' => "datetime DEFAULT '1900-01-01 00:00:00'",
						'created_user_id' => 'int(10) unsigned  DEFAULT 1',
						'created_date' => "datetime DEFAULT '1900-01-01 00:00:00'"
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('ophtroperationnote_trabectome_complication_lmui_fk',
				'ophtroperationnote_trabectome_complication',
				'last_modified_user_id', 'user', 'id');
		$this->addForeignKey('ophtroperationnote_trabectome_complication_cui_fk',
				'ophtroperationnote_trabectome_complication',
				'created_user_id', 'user', 'id');

		$this->createTable('et_ophtroperationnote_trabectome', array(
						'id' => 'pk',
						'event_id' => 'int(10) unsigned NOT NULL',
						'power_id' => 'integer NOT NULL',
						'blood_reflux' => 'boolean',
						'hpmc' => 'boolean',
						'eyedraw' => 'text',
						'description' => 'text',
						'complication_other' => 'text',
						'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
						'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
						'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
						'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
						'KEY `et_ophtroperationnote_trabectome_lmui_fk` (`last_modified_user_id`)',
						'KEY `et_ophtroperationnote_trabectome_cui_fk` (`created_user_id`)',
						'KEY `et_ophtroperationnote_trabectome_ev_fk` (`event_id`)',
						'CONSTRAINT `et_ophtroperationnote_trabectome_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
						'CONSTRAINT `et_ophtroperationnote_trabectome_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
						'CONSTRAINT `et_ophtroperationnote_trabectome_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('et_ophtroperationnote_trabectome_power_id',
			'et_ophtroperationnote_trabectome', 'power_id',
			'ophtroperationnote_trabectome_power', 'id');

		$this->createTable('ophtroperationnote_trabectome_comp_ass', array(
						'id' => 'pk',
						'element_id' => 'integer NOT NULL',
						'complication_id' => 'integer NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned DEFAULT 1',
						'last_modified_date' => "datetime DEFAULT '1900-01-01 00:00:00'",
						'created_user_id' => 'int(10) unsigned  DEFAULT 1',
						'created_date' => "datetime DEFAULT '1900-01-01 00:00:00'"
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('ophtroperationnote_trabectome_comp_ass_lmui_fk',
				'ophtroperationnote_trabectome_comp_ass',
				'last_modified_user_id', 'user', 'id');
		$this->addForeignKey('ophtroperationnote_trabectome_comp_ass_cui_fk',
				'ophtroperationnote_trabectome_comp_ass',
				'created_user_id', 'user', 'id');

		$this->versionExistingTable('ophtroperationnote_trabectome_power');
		$this->versionExistingTable('ophtroperationnote_trabectome_complication');
		$this->versionExistingTable('et_ophtroperationnote_trabectome');
		$this->versionExistingTable('ophtroperationnote_trabectome_comp_ass');

		$migrations_path = dirname(__FILE__);
		$this->initialiseData($migrations_path);
	}

	public function down()
	{
		$this->dropTable('ophtroperationnote_trabectome_comp_ass_version');
		$this->dropTable('et_ophtroperationnote_trabectome_version');
		$this->dropTable('ophtroperationnote_trabectome_complication_version');
		$this->dropTable('ophtroperationnote_trabectome_power_version');
		$this->dropTable('ophtroperationnote_trabectome_comp_ass');
		$this->dropTable('et_ophtroperationnote_trabectome');
		$this->dropTable('ophtroperationnote_trabectome_complication');
		$this->dropTable('ophtroperationnote_trabectome_power');

		$element_type = $this->dbConnection->createCommand()->select("id")->from("element_type")->where("class_name = ?", array('Element_OphTrOperationnote_Trabectome'))->queryRow();
		$element_type_id = $element_type['id'];
		$this->delete('ophtroperationnote_procedure_element', 'element_type_id = ?', array($element_type_id));
		$this->delete('element_type', 'id = ?', array($element_type_id));
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}