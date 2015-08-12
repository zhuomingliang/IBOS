<?php

/**
 * user_group表的数据层操作文件
 *
 * @author banyanCheung <banyan@ibos.com.cn>
 * @link http://www.ibos.com.cn/
 * @copyright Copyright &copy; 2012-2013 IBOS Inc
 */
/**
 *  user_group表的数据层操作
 * 
 * @package application.modules.user.model
 * @version $Id: UserGroup.php 4064 2014-09-03 09:13:16Z zhangrong $
 * @author banyanCheung <banyan@ibos.com.cn>
 */

namespace application\modules\user\model;

use application\core\model\Model;

class UserGroup extends Model {

    public static function model( $className = __CLASS__ ) {
        return parent::model( $className );
    }

    public function tableName() {
        return '{{user_group}}';
    }

    /**
     * 查找下一等级的用户组
     * @param integer $creditsLower
     * @return array
     */
    public function fetchNextLevel( $creditsLower ) {
        $criteria = array(
            'condition' => 'creditshigher = :lower',
            'params' => array( ':lower' => $creditsLower ),
            'limit' => 1
        );
        return $this->fetch( $criteria );
    }

    /**
     * 根据积分来查找一个对应的用户组
     * @param mixed $credits
     * @return array
     */
    public function fetchByCredits( $credits ) {
        if ( is_array( $credits ) ) {
            $creditsf = intval( $credits[0] );
            $creditse = intval( $credits[1] );
        } else {
            $creditsf = $creditse = intval( $credits );
        }
        $criteria = array(
            'select' => 'title,gid',
            'condition' => ':creditsf>=creditshigher AND :creditse<creditslower',
            'params' => array( ':creditsf' => $creditsf, ':creditse' => $creditse ),
            'limit' => 1
        );
        return $this->fetch( $criteria );
    }

    /**
     * 根据Id字符串删除非系统用户组
     * @param string $ids
     * @author banyan <banyan@ibos.com.cn>
     * @return integer 删除的条数
     */
    public function deleteById( $ids ) {
        $id = explode( ',', trim( $ids, ',' ) );
        return parent::deleteByPk( $id, "`system` = '0'" );
    }

}