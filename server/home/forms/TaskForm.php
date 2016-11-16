<?php
namespace xoa\home\forms;

use xoa\common\models\Worker;
use xoa\common\models\Task;

/**
 * 任务表单
 * @author KK
 */
class TaskForm extends \yii\base\Model{
	const SCENE_ADD = 'add';
	/**
	 * @var string 任务标题
	 */
	public $title = '';
	
	/**
	 * @var string 任务详情
	 */
	public $detail = '';
	
	/**
	 * @var array 负责人ID集合
	 */
	public $workerIds = '';
	
	/**
	 * @var array 相关人员ID集合
	 */
	public $relatedMemberIds = '';
	
	/**
	 * @var array 负责人集合
	 */
	public $workers = '';
	
	/**
	 * @var array 相关人员集合
	 */
	public $relatedMembers = '';
	
	/**
	 * @var string 限制完成时间
	 */
	public $limitTime = '';
	
	/**
	 * @inheritedoc
	 * @author KK
	 */
	public function rules(){
		return [
			[['title', 'workerIds'], 'required'],
			['title', 'string', 'length' => [4, 30], 'message' => '任务标题在4到30个字之间'],
			['detail', 'string', 'length' => [4, 65535], 'message' => '任务详情在4到65535个字之间'],
			[['workerIds', 'relatedMemberIds'], 'each', 'rule' => ['integer']],
			[['workerIds', 'relatedMemberIds'], 'validateMemberIds'],
		];
	}
	
	/**
	 * @inheritedoc
	 * @author KK
	 */
	public function scenarios() {
		return [
			static::SCENE_ADD => ['title', 'detail', 'workerIds', 'relatedMemberIds', 'limitTime'],
		];
	}
	
	/**
	 * 验证成员ID集
	 * @param string $attributeName 被验证的属性名称
	 * @param array $params 验证的附加参数
	 * @return Task
	 * @throws \yii\base\ErrorException
	 */
	public function validateMemberIds($attributeName, $params){
		$workers = Worker::findAll($this->{$attributeName});
		if(!$workers){
			$role = $attributeName == 'workerIds' ? '负责人' : '成员';
			$this->addError($attributeName, '无效的' . $role . 'ID');
		}else{
			$attr = $attributeName == 'workerIds' ? 'workers' : 'relatedMembers';
			$this->{$attr} = $workers;
		}
	}
	
	/**
	 * 添加任务
	 * @author KK
	 * @return Task
	 * @throws \yii\base\ErrorException
	 */
	public function add(){
		if(!$this->validate()){
			debug($this->errors,11);
			return false;
		}
		$task = new Task([
			'title' => $this->title,
			'detail' => $this->detail,
			'worker_ids' => $this->workerIds,
			'related_member_ids' => $this->relatedMemberIds,
			'limit_time' => $this->limitTime,
		]);
		if(!$task->save()){
			throw new \yii\base\ErrorException('添加任务失败');
		}else{
			return $task;
		}
	}
}