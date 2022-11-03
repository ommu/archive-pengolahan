<?php
/**
 * ArchivePengolahanPenyerahanJenis
 *
 * ArchivePengolahanPenyerahanJenis represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 12 October 2022, 19:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis as ArchivePengolahanPenyerahanJenisModel;
use yii\helpers\ArrayHelper;

class ArchivePengolahanPenyerahanJenis extends ArchivePengolahanPenyerahanJenisModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'penyerahan_id', 'tag_id', 'creation_id',
                'penyerahanTypeId'], 'integer'],
			[['creation_date', 
                'tagBody', 'penyerahanArsip', 'creationDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = ArchivePengolahanPenyerahanJenisModel::find()
                ->alias('t')
                ->select(['t.*', 'count(t.id) as penyerahans']);
        } else {
            $column = ArrayHelper::merge($column, ['count(t.id) as penyerahans']);
            $query = ArchivePengolahanPenyerahanJenisModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'tag tag', 
			'penyerahan penyerahan', 
			// 'penyerahan.type penyerahan', 
			// 'creation creation'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['tagBody', '-tagBody'])) || 
            (isset($params['tagBody']) && $params['tagBody'] != '')
        ) {
            $query->joinWith(['tag tag']);
        }
        // if ((isset($params['sort']) && in_array($params['sort'], ['penyerahanArsip', '-penyerahanArsip'])) || (
        //     (isset($params['penyerahanArsip']) && $params['penyerahanArsip'] != '') ||
        //     (isset($params['penyerahanTypeId']) && $params['penyerahanTypeId'] != '') ||
        //     (isset($params['type']) && $params['type'] != '')
        // )) {
        //     $query->joinWith(['penyerahan penyerahan']);
        // }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || 
            (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')
        ) {
            $query->joinWith(['creation creation']);
        }
        if (isset($params['sort']) && in_array($params['sort'], ['penyerahanTypeId', '-penyerahanTypeId'])) {
            $query->joinWith(['type type']);
        }

        $query->groupBy(['t.tag_id']);
        if ($this->isData) {
            $query->groupBy(['t.id']);
        }

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['tagBody'] = [
			'asc' => ['tag.body' => SORT_ASC],
			'desc' => ['tag.body' => SORT_DESC],
		];
		$attributes['penyerahanArsip'] = [
			'asc' => ['penyerahan.pencipta_arsip' => SORT_ASC],
			'desc' => ['penyerahan.pencipta_arsip' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['penyerahans'] = [
			'asc' => ['penyerahans' => SORT_ASC],
			'desc' => ['penyerahans' => SORT_DESC],
		];
		$attributes['penyerahanTypeId'] = [
			'asc' => ['type.type_name' => SORT_ASC],
			'desc' => ['type.type_name' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
        $query->andFilterWhere([
			't.id' => $this->id,
			't.penyerahan_id' => isset($params['penyerahan']) ? $params['penyerahan'] : $this->penyerahan_id,
			't.tag_id' => isset($params['tag']) ? $params['tag'] : $this->tag_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'penyerahan.type_id' => $this->penyerahanTypeId,
		]);

		$query->andFilterWhere(['like', 'tag.body', $this->tagBody])
			->andFilterWhere(['or',
                ['like', 'penyerahan.kode_box', $this->penyerahanArsip],
                ['like', 'penyerahan.pencipta_arsip', $this->penyerahanArsip]
            ])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
            ->andFilterWhere(['<>', 'penyerahan.publish', '2']);

		return $dataProvider;
	}
}
