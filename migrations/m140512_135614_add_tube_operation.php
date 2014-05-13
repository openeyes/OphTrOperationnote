<?php

class m140512_135614_add_tube_operation extends OEMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select("*")->from("event_type")->where("class_name = :class_name",array(":class_name"=>"OphTrOperationnote"))->queryRow();

		$ets = $this->insertOEElementType(array('Element_OphTrOperationnote_GlaucomaTube' =>
						array(
								'name' => 'Glaucoma Tube' ,
								'display_order' => 15,
								'required' => false,
								'default' => false
						)), $event_type['id']);

		$element_type_id = $ets[0];

		$this->createTable('ophtroperationnote_gt_plateposition', array(
					'id' => 'pk',
						'name' => 'string NOT NULL',
						'active' => 'boolean NOT NULL DEFAULT true',
						'display_order' => 'integer NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned DEFAULT 1',
						'last_modified_date' => "datetime DEFAULT '1900-01-01 00:00:00'",
						'created_user_id' => 'int(10) unsigned  DEFAULT 1',
						'created_date' => "datetime DEFAULT '1900-01-01 00:00:00'"
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('ophtroperationnote_gt_plateposition_lmui_fk',
				'ophtroperationnote_gt_plateposition',
				'last_modified_user_id', 'user', 'id');
		$this->addForeignKey('ophtroperationnote_gt_plateposition_cui_fk',
				'ophtroperationnote_gt_plateposition',
				'created_user_id', 'user', 'id');

		$this->createTable('ophtroperationnote_gt_tubeposition', array(
						'id' => 'pk',
						'name' => 'string NOT NULL',
						'active' => 'boolean NOT NULL DEFAULT true',
						'display_order' => 'integer NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned DEFAULT 1',
						'last_modified_date' => "datetime DEFAULT '1900-01-01 00:00:00'",
						'created_user_id' => 'int(10) unsigned  DEFAULT 1',
						'created_date' => "datetime DEFAULT '1900-01-01 00:00:00'"
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('ophtroperationnote_gt_tubeposition_lmui_fk',
				'ophtroperationnote_gt_tubeposition',
				'last_modified_user_id', 'user', 'id');
		$this->addForeignKey('ophtroperationnote_gt_tubeposition_cui_fk',
				'ophtroperationnote_gt_tubeposition',
				'created_user_id', 'user', 'id');

		$this->createTable('ophtroperationnote_gt_ligated', array(
						'id' => 'pk',
						'name' => 'string NOT NULL',
						'active' => 'boolean NOT NULL DEFAULT true',
						'display_order' => 'integer NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned DEFAULT 1',
						'last_modified_date' => "datetime DEFAULT '1900-01-01 00:00:00'",
						'created_user_id' => 'int(10) unsigned  DEFAULT 1',
						'created_date' => "datetime DEFAULT '1900-01-01 00:00:00'"
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('ophtroperationnote_gt_ligated_lmui_fk',
				'ophtroperationnote_gt_ligated',
				'last_modified_user_id', 'user', 'id');
		$this->addForeignKey('ophtroperationnote_gt_ligated_cui_fk',
				'ophtroperationnote_gt_ligated',
				'created_user_id', 'user', 'id');

		$this->createTable('et_ophtroperationnote_glaucomatube', array(
						'id' => 'pk',
						'event_id' => 'int(10) unsigned NOT NULL',
						'plate_position_id' => 'integer NOT NULL',
						'plate_limbus' => 'integer NOT NULL',
						'tube_position_id' => 'integer NOT NULL',
						'stent' => 'boolean',
						'ligature' => 'boolean',
						'ligated_id' => 'integer',
						'slit' => 'boolean',
						'visco_in_ac' => 'boolean',
						'flow_tested' => 'boolean',
						'comments' => 'varchar(4096) NOT NULL',
						'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
						'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
						'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
						'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
						'KEY `et_ophtroperationnote_glautub_lmui_fk` (`last_modified_user_id`)',
						'KEY `et_ophtroperationnote_glautub_cui_fk` (`created_user_id`)',
						'KEY `et_ophtroperationnote_glautub_ev_fk` (`event_id`)',
						'CONSTRAINT `et_ophtroperationnote_glautub_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
						'CONSTRAINT `et_ophtroperationnote_glautub_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
						'CONSTRAINT `et_ophtroperationnote_glautub_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('et_ophtroperationnote_glaucomatube_ppos_fk',
				'et_ophtroperationnote_glaucomatube',
				'plate_position_id', 'ophtroperationnote_gt_plateposition', 'id');
		$this->addForeignKey('et_ophtroperationnote_glaucomatube_tpos_fk',
				'et_ophtroperationnote_glaucomatube',
				'tube_position_id', 'ophtroperationnote_gt_tubeposition', 'id');
		$this->addForeignKey('et_ophtroperationnote_glaucomatube_lig_fk',
				'et_ophtroperationnote_glaucomatube',
				'ligated_id', 'ophtroperationnote_gt_ligated', 'id');

		$this->versionExistingTable('ophtroperationnote_gt_plateposition');
		$this->versionExistingTable('ophtroperationnote_gt_tubeposition');
		$this->versionExistingTable('ophtroperationnote_gt_ligated');
		$this->versionExistingTable('et_ophtroperationnote_glaucomatube');

		$procs = $this->dbConnection->createCommand()->select("id")->from("proc")->where("snomed_code in (:snomds)",array(":snomeds" => '265291005,440587008'))->queryAll();
		foreach ($procs as $p) {
			$this->insert('ophtroperationnote_procedure_element',array('procedure_id'=>$p['id'],'element_type_id'=>$element_type_id));
		}
		$migrations_path = dirname(__FILE__);
		$this->initialiseData($migrations_path);
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_glaucomatube_version');
		$this->dropTable('ophtroperationnote_gt_plateposition_version');
		$this->dropTable('ophtroperationnote_gt_tubeposition_version');
		$this->dropTable('ophtroperationnote_gt_ligated_version');
		$this->dropTable('et_ophtroperationnote_glaucomatube');
		$this->dropTable('ophtroperationnote_gt_plateposition');
		$this->dropTable('ophtroperationnote_gt_tubeposition');
		$this->dropTable('ophtroperationnote_gt_ligated');
		$this->delete('element_type', 'class_name = ?', array('Element_OphTrOperationnote_GlaucomaTube'));
	}

}