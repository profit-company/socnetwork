<?php

namespace app\controllers;

use yii;
use app\models\UserFriends;
use app\components\FrontendController;
use yii\web\NotFoundHttpException;
use app\components\extend\ActiveForm;
use yii\helpers\Json;
use app\components\extend\Html;
use app\models\search\UserFriendsSearch;
use app\models\User;

/**
 * UserFriendsController implements the CRUD actions for UserFriends model.
 */
class FriendsController extends FrontendController
{

    /**
     * Lists all UserFriends models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $model = new UserFriends;
        $user = User::findById(($id === null ? yii::$app->user->id : (int) $id));
        if (!$user) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $searchModel = new UserFriendsSearch();
        $searchModel->user_id = $user->primaryKey;
        $friends = $searchModel->searchFriends();
        $requests = $searchModel->searchRequests();
        return $this->render('index', [
                    'requests' => $requests,
                    'friends' => $friends,
                    'model' => $model,
                    'user' => $user,
        ]);
    }

    /**
     * invite friend
     */
    public function actionInvite($id)
    {
        $model = $this->findModel($id, false);
        if (!$model) {
            $model = new UserFriends();
            $model->user_id = (int) $id;
            $model->sender_id = yii::$app->user->id;
        }
        $model->status = UserFriends::STATUS_REQUEST;
        $this->setMessage(($model->save() ? 'success' : 'error'));
    }

    /**
     * cancel invitation
     */
    public function actionCancel($id)
    {
        return $this->actionDelete($id);
    }

    /**
     * accept friend request
     */
    public function actionAccept($id)
    {
        $model = $this->findModel($id);
        if ($model->sender_id == yii::$app->user->id) {
            $this->setMessage('error', yii::$app->l->t('cheating is not fair'));
            return null;
        }
        $model->status = UserFriends::STATUS_IS_FRIEND;
        $this->setMessage(($model->save() ? 'success' : 'error'));
    }

    /**
     * reject friend request
     */
    public function actionReject($id)
    {
        return $this->actionDelete($id);
    }

    /**
     * reject friend request
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id, false);
        if ($model) {
            $this->setMessage(($model->delete() ? 'success' : 'error'));
        }
    }

    /**
     * Finds the UserFriends model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $friendId
     * @return UserFriends the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($friendId, $throwError = true)
    {
        $q = UserFriends::find();
        $q->andWhere('(user_id=:uid AND sender_id=:sid) OR (sender_id=:uid AND user_id=:sid)', [
            'uid' => yii::$app->user->id,
            'sid' => (int) $friendId,
        ]);
        if (($model = $q->one()) !== null) {
            return $model;
        } else {
            if ($throwError) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }

}
