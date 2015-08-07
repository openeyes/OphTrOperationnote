<?php

class m150806_135453_per_operative_difficulty extends OEMigration
{
	protected $difficulties = array(
		'Uncooperative patient',
		'Uncontrolled eye movement',
		'Deep-set eye',
		'Shallow anterior chamber',
		'Pupil - Medium (manipulation not required but increased surgical difficulty)',
		'Pupil - Small (surgical manipulation necessary)',
		'Posterior Synechiae',
		'Atonic pupil',
		'Floppy Iris',
		'Pseudoexfoliation',
		'Phacodonesis',
		'Corneal Opacity',
		'Poor View',
		'Other',
	);

	public function up()
	{
		$this->createOETable('ophtroperationnote_difficulty', array(
			'id' => 'pk',
			'difficulty' => 'varchar(250) NOT NULL',
		), true);

		$this->createOETable('et_ophtroperationnote_difficulties', array(
			'id' => 'pk',
			'event_id' => 'int(10) unsigned NOT NULL',
			'CONSTRAINT `et_ophtroperationnote_diff_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)'
		), true);

		$this->createOETable('ophtroperationnote_difficulty_assignment', array(
			'id' => 'pk',
			'ophtroperationnote_difficulties_id' => 'int(11) NOT NULL',
			'difficulty_id' => 'int(11) NOT NULL',
			'CONSTRAINT `ophtroperationnote_difficulty_assignment_diff_fk` FOREIGN KEY (`difficulty_id`) REFERENCES `ophtroperationnote_difficulty` (`id`)',
			'CONSTRAINT `ophtroperationnote_difficulty_assignment_diff_et_fk` FOREIGN KEY (`ophtroperationnote_difficulties_id`) REFERENCES `et_ophtroperationnote_difficulties` (`id`)'
		));

		$this->insert('element_type', array(
			'name' => 'Per-Operative difficulties',
			'class_name' => 'Element_OphTrOperationnote_Difficulties',
			'event_type_id' => '4',
			'display_order' => '50'
		));

		foreach($this->difficulties as $difficulty){
			$this->insert('ophtroperationnote_difficulty', array('difficulty' => $difficulty));
		}
	}

	public function down()
	{
		$this->dropTable('ophtroperationnote_difficulty_assignment');
		$this->dropOETable('et_ophtroperationnote_difficulties', true);
		$this->dropOETable('ophtroperationnote_difficulty', true);

		$this->delete('element_type', 'class_name = "Element_OphTrOperationnote_Difficulties"');
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