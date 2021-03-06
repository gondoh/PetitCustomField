<?php
/**
 * [Controller] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
App::uses('PetitCustomFieldApp', 'PetitCustomField.Controller');
class PetitCustomFieldConfigFieldsController extends PetitCustomFieldAppController {
/**
 * コントローラー名
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigFields';
	
/**
 * モデル
 * 
 * @var array
 */
	public $uses = array('PetitCustomField.PetitCustomFieldConfigField');
	
/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'プチ・カスタムフィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_configs', 'action' => 'index')),
	);
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = 'フィールド設定';
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		// カスタムフィールド設定からコンテンツIDを取得してセット
		if (!empty($this->request->params['pass'][0])) {
			$configId = $this->request->params['pass'][0];
			$configData = $this->PetitCustomFieldConfigField->PetitCustomFieldConfig->find('first', array(
				'conditions' => array('PetitCustomFieldConfig.id' => $configId),
				'recursive' => -1,
			));
			$contentId = $configData['PetitCustomFieldConfig']['content_id'];
			$this->set('contentId', $contentId);
		}
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $configId
 * @param int $foreignId
 * @return void
 */
	public function admin_edit($configId = null, $foreignId = null) {
		$this->pageTitle = $this->adminTitle . '編集';
		$this->help = 'petit_custom_field_config_fields';
		$deletable = true;
		
		if (!$configId || !$foreignId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		$this->crumbs[] = array('name' => 'フィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
		
		if (empty($this->request->data)) {
			// $data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
			$data = $this->{$this->modelClass}->getSection($foreignId, $this->modelClass);
			if ($data) {
				$this->request->data = array($this->modelClass => $data);
			}
		} else {
			// バリデーション重複チェックのため、foreign_id をモデルのプロパティに持たせる
			$this->PetitCustomFieldConfigField->foreignId = $foreignId;
			if ($this->PetitCustomFieldConfigField->validateSection($this->request->data, 'PetitCustomFieldConfigField')) {
				if ($this->PetitCustomFieldConfigField->saveSection($foreignId, $this->request->data, 'PetitCustomFieldConfigField')) {
					$message = '「'. $this->request->data['PetitCustomFieldConfigField']['name'] .'」の更新が完了しました。';
					$this->setMessage($message);
					$this->redirect(array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
				} else {
					$this->setMessage('入力エラーです。内容を修正して下さい。', true);
				}
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('configId', $configId);
		$this->set('foreignId', $foreignId);
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->set('deletable', $deletable);
		$this->render('form');
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $configId
 * @return void
 */
	public function admin_add($configId = null) {
		$this->pageTitle = $this->adminTitle . '追加';
		$this->help = 'petit_custom_field_config_fields';
		$this->crumbs[] = array('name' => 'カスタムフィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
		$deletable = false;
		$foreignId = $this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->getMax('field_foreign_id') + 1;
		
		if (empty($this->request->data)) {
			if (!$configId) {
				$this->setMessage('無効な処理です。', true);
				$this->redirect(array('action' => 'index'));
			}
			$this->request->data = $this->PetitCustomFieldConfigField->defaultValues();
		} else {
			if ($this->PetitCustomFieldConfigField->validateSection($this->request->data, 'PetitCustomFieldConfigField')) {
				if ($this->PetitCustomFieldConfigField->saveSection($foreignId, $this->request->data, 'PetitCustomFieldConfigField')) {

					// リンクテーブルにデータを追加する
					$saveData = array(
						'PetitCustomFieldConfigMeta' => array(
							'petit_custom_field_config_id' => $configId,
							'field_foreign_id'	=> $foreignId,
						),
					);
					// load しないと順番が振られない。スコープが効かない。
					$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->Behaviors->load(
						'PetitCustomField.List', array('scope' => 'petit_custom_field_config_id')
					);
					$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->create($saveData);
					$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->save($saveData);

					$message = '「'. $this->request->data['PetitCustomFieldConfigField']['name'] .'」の追加が完了しました。';
					$this->setMessage($message);
					$this->redirect(array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
				} else {
					$this->setMessage('入力エラーです。内容を修正して下さい。', true);
				}
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('configId', $configId);
		$this->set('foreignId', $foreignId);
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->set('deletable', $deletable);
		$this->render('form');
	}
	
/**
 * [ADMIN] 削除
 * 
 * @param int $configId
 * @param int $foreignId
 * @return void
 */
	public function admin_delete($configId = null, $foreignId = null) {
		if (!$configId || !$foreignId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		// 削除前にメッセージ用にカスタムフィールドを取得する
		$data = $this->PetitCustomFieldConfigField->getSection($foreignId, 'PetitCustomFieldConfigField');
		
		if ($this->PetitCustomFieldConfigField->resetSection($foreignId)) {
			$message = '「' . $data['PetitCustomFieldConfigField']['name'] . '」を削除しました。';
			$this->setMessage($message);
			$this->redirect(array('action' => 'index', $configId));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index', $configId));
	}
	
/**
 * 一覧用の検索条件を生成する
 *
 * @param array $data
 * @return array $conditions
 */
	protected function _createAdminIndexConditions($data) {	
		$conditions = array();
		$blogContentId = '';
		
		if (isset($data['PetitCustomFieldConfigField']['content_id'])) {
			$blogContentId = $data['PetitCustomFieldConfigField']['content_id'];
		}
		
		unset($data['_Token']);
		unset($data['PetitCustomFieldConfigField']['content_id']);
		
		// 条件指定のないフィールドを解除
		if (!empty($data['PetitCustomFieldConfigField'])) {
			foreach ($data['PetitCustomFieldConfigField'] as $key => $value) {
				if ($value === '') {
					unset($data['PetitCustomFieldConfigField'][$key]);
				}
			}
			if ($data['PetitCustomFieldConfigField']) {
				$conditions = $this->postConditions($data);
			}
		}
		
		if ($blogContentId) {
			$conditions = array(
				'PetitCustomFieldConfigField.content_id' => $blogContentId
			);
		}
		
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
}
