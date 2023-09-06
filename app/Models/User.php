<?php

namespace App\Models;

use App\Models\PlanSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Classes\Helper\StripeConnect;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, AuthenticationLogable, SoftDeletes;

    protected $table = 'users';

    const PLAN_PRICE_PERMISSION_1 = 27;
    const SELLER_EMAIL = "seller@dev.com";
    const USER_PHONE_CHECK = "00000000000000";
    const MAIN_SELLER_ID = 2;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get All Members.
     */
    public static function getMyMember($memberId, $includeParent = false) {
        $sql = "WITH RECURSIVE cte (id, name, email, parent_id, tree_pos, profile_image, thumbnail_image, products, transactions, members, is_favourite) as (
                  SELECT     id,
                             name,
                             email,
                             parent_id,
                             tree_pos,
                             profile_image,
                             thumbnail_image, 
                             0 as products,
                             0 as transactions,
                             0 as members,
                             (SELECT count(uf1.id) FROM user_favourites uf1 WHERE uf1.user_id = ? AND uf1.member_id = users.id) as is_favourite
                  FROM       users
                  WHERE      parent_id = ?
                  UNION ALL
                  SELECT     u.id,
                             u.name,
                             u.email,
                             u.parent_id,
                             u.tree_pos,
                             u.profile_image,
                             u.thumbnail_image,
                             0 as products,
                             0 as transactions,
                             0 as members,
                             (SELECT count(uf1.id) FROM user_favourites uf1 WHERE uf1.user_id = ? AND uf1.member_id = u.id) as is_favourite
                  FROM       users u
                  INNER JOIN cte
                          ON u.parent_id = cte.id
                )
                SELECT * FROM cte;";
        $result = DB::select($sql, [$memberId,$memberId,$memberId]);
        $user = User::findOrFail($memberId);
        $rootElement = new \stdClass();
        $rootElement->id = $user->id;
        $rootElement->name = $user->name;
        $rootElement->email = $user->email;
        $rootElement->parent_id = ($includeParent && $user->parent_id)?$user->parent_id:0;
        $rootElement->tree_pos = $user->tree_pos;
        $rootElement->profile_image = $user->profile_image;
        $rootElement->products = 0;
        $rootElement->transactions = 0;
        $rootElement->members = 0;
        $rootElement->is_favourite = false;
        array_unshift($result, $rootElement);

        if($includeParent && $user->parent_id){
            $parent = User::findOrFail($user->parent_id);
            $rootElement = new \stdClass();
            $rootElement->id = $parent->id;
            $rootElement->name = $parent->name;
            $rootElement->email = $parent->email;
            $rootElement->parent_id = 0;
            $rootElement->tree_pos = $parent->tree_pos;
            $rootElement->profile_image = $parent->profile_image;
            $rootElement->products = 0;
            $rootElement->transactions = 0;
            $rootElement->members = 0;
            $rootElement->is_favourite = false;
            $rootElement->hide_profile = 1;
            array_unshift($result, $rootElement);
        }
        return $result;
    }

    /**
     * Get All Members.
     */
    public static function getMemberByLevel($memberId, $includeParent = false) {
        $sql = "WITH RECURSIVE cte (level, id, name, email, parent_id, tree_pos, profile_image, products, transactions, members, is_favourite) AS (
            SELECT
                1 AS level,
                id,
                name,
                email,
                parent_id,
                tree_pos,
                profile_image,
                0 AS products,
                0 AS transactions,
                0 AS members,
                (SELECT COUNT(uf1.id) FROM user_favourites uf1 WHERE uf1.user_id = ? AND uf1.member_id = users.id) AS is_favourite
            FROM users
            WHERE parent_id = ?
            UNION ALL
            SELECT
                cte.level + 1,
                u.id,
                u.name,
                u.email,
                u.parent_id,
                u.tree_pos,
                u.profile_image,
                0 AS products,
                0 AS transactions,
                0 AS members,
                (SELECT COUNT(uf1.id) FROM user_favourites uf1 WHERE uf1.user_id = ? AND uf1.member_id = u.id) AS is_favourite
            FROM users u
            INNER JOIN cte ON u.parent_id = cte.id
            WHERE cte.level < 1
        )
        SELECT * FROM cte;";
        $result = DB::select($sql, [$memberId,$memberId,$memberId]);
        $user = User::findOrFail($memberId);
        $rootElement = new \stdClass();
        $rootElement->id = $user->id;
        $rootElement->name = $user->name;
        $rootElement->email = $user->email;
        $rootElement->parent_id = ($includeParent && $user->parent_id)?$user->parent_id:0;
        $rootElement->tree_pos = $user->tree_pos;
        $rootElement->profile_image = $user->profile_image;
        $rootElement->products = 0;
        $rootElement->transactions = 0;
        $rootElement->members = 0;
        $rootElement->is_favourite = false;
        array_unshift($result, $rootElement);

        if($includeParent && $user->parent_id){
            $parent = User::findOrFail($user->parent_id);
            $rootElement = new \stdClass();
            $rootElement->id = $parent->id;
            $rootElement->name = $parent->name;
            $rootElement->email = $parent->email;
            $rootElement->parent_id = 0;
            $rootElement->tree_pos = $parent->tree_pos;
            $rootElement->profile_image = $parent->profile_image;
            $rootElement->products = 0;
            $rootElement->transactions = 0;
            $rootElement->members = 0;
            $rootElement->is_favourite = false;
            $rootElement->hide_profile = 1;
            array_unshift($result, $rootElement);
        }
        
        return $result;
    }

    /**
     * Get downline ids
     */
    public static function getDownlineIds($userId = NULL) {
        if($userId === NULL){
            $userId = Auth::id();
        }
        $members = array_column(self::getMyMember($userId), 'id');
        return array_diff($members, [$userId]);
    }

    /**
     * Get downline count
     */
    public static function getDownlineCount() {
        return count(self::getDownlineIds());
    }

    /**
     * Get user tree
     */
    public static function getUserTree($parentId) {
        $members = self::getMyMember($parentId);
        $memberArray = [];
        foreach($members as $member){
            $memberArray[$member->id] = (array)$member;
            $memberArray[$member->id]['type'] = 'Member';
            $memberArray[$member->id]['description'] = 'Image';
            $memberArray[$member->id]['icon'] = '/assets/images/user-icon2.png';
            $memberArray[$member->id]['children'] = [];
        }

        foreach($memberArray as $id => &$value)
        {
            # check if there is a parent
            if ($pid = $value['parent_id'])
            {
                $memberArray[$pid]['children'][] =& $value; # add child to parent
            }
        }
        unset($value);

        return $memberArray[$parentId];
    }

    /**
     * Find empty node
     */
    public static function findEmptyNode($memberId, $memberTreeData = NULL, $position = 'any') {
        return $memberId; // This will allow having more than 2 direct downlines for a member

        $empty = 0;
        if(!$memberTreeData){
            $memberTreeData = self::getUserTree($memberId);
        }
        if($memberTreeData){
            if(count($memberTreeData['children']) == 0){
                return $memberTreeData['id'];
            }
            if (count($memberTreeData['children']) == 1){
                if($memberTreeData['children'][0]['tree_pos'] == 'left' && in_array($position, ['right', 'any'])){
                    return $memberTreeData['id'];
                }
                if($memberTreeData['children'][0]['tree_pos'] == 'right' && in_array($position, ['left', 'any'])){
                    return $memberTreeData['id'];
                }
                $empty = self::findEmptyNode($memberTreeData['children'][0]['id'], $memberTreeData['children'][0], 'any');
            } else {
                if(in_array($position, ['left', 'any'])){
                    $empty = self::findEmptyNode($memberTreeData['children'][0]['id'], $memberTreeData['children'][0], 'any');
                } else {
                    $empty = self::findEmptyNode($memberTreeData['children'][0]['id'], isset($memberTreeData['children'][1]) ? $memberTreeData['children'][1] : $memberTreeData['children'][0], 'any');
                }
            }
        }
        return $empty;

    }

	/**
	 * Boards
	 */
	public function boards() {
		return $this->hasMany(Board::class);
	}

	/**
	 * Current board
	 */
	public function board() {
		return $this->hasOne(Board::class)->where('is_current', 1);
	}

    /**
     * Root member
     */
    public function isRootMember() {
        return empty(Auth::user()->parent_id) || (Auth::id() === Auth::user()->root_id);
    }

    /**
     * Get root member id
     */
    public function getRootMemberId() {
        return Auth::user()->root_id;
    }

    /**
     * Get the user's active stripe cards.
     */
    public function activeStripeCard() {
        return $this->hasOne(StripeCard::class)->where('is_active', 1);
    }

    /**
     * Get the user's stripe cards.
     */
    public function stripeCards() {
        return $this->hasMany(StripeCard::class);
    }

    /**
     * Create stripe customer if null
     */
    public function createStripeCustomerIfNull() {
        if(empty($this->stripe_customer_id)) {
            // Create customer in stripe
            $stripeData = StripeConnect::createCustomer([
                'name' => $this->name,
                'email' => $this->email
            ]);
            if($stripeData['res_status'] == 'success'){
                $this->stripe_customer_id = $stripeData['id'];
                $this->save();
            }
            return $stripeData;
        } else {
            return ['res_status' => 'success'];
        }
    }

    /**
     * User experience
     */
    public function userExperience() {
        return $this->hasMany(UserExperience::class);
    }

    /**
     * User education
     */
    public function userEducation() {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * Get upline
     */
    public function getUpline() {
        if($this->parent_id){
            return self::find($this->parent_id);
        }
        return false;
    }

    /**
     * Get upline ids
     */
    public static function getUplineArray($user) {
        $memberIds = [];
        $parentUser = $user->parent_id;
        do {
            $x = User::find($parentUser);
            if ($x) {
                $parentUser = $x->parent_id;
                if (!in_array($x->id, $memberIds, true)) {
                    array_unshift($memberIds, $x->id);
                }
            }
        } while ($x);
        return $memberIds;
    }

    /**
     * Get upline name
     */
    public function getUplineName() {
        if($this->getUpline()){
            $upline = $this->getUpline();
            return $upline->name;
        }
    }

    /**
     * Categories
     */
    public function categories() {
        return $this->belongsToMany(Category::class,'user_category');
    }

    /**
     * Videos
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class)->withTimestamps();
    }

    /**
     * Event reps
     */
    public function eventReps() {
        return $this->belongsToMany(Event::class, 'event_reps', 'member_id', 'event_id');
    }

    /**
     * User plan
     */
    public function userPlan() {
        return $this->hasOne(UserPlan::class);
    }

    /**
     * Assign free plan
     */
    public function assignFreePlan() {
        UserPlan::query()->firstOrCreate([
            'user_id' => $this->id,
            'plan_id' => Plan::FREE_PLAN_ID,
        ], [
            'expiration' => Carbon::now()->addDays(14),
            'status' => 'active'
        ]);
    }

    /**
     * Get full name
     */
    public function getFullName() {
        return ucfirst($this->name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Presentator user
     */
    public static function presentatorUser() {
        $user = Auth::User();
        $rootId = !empty($user->root_id) ? $user->root_id : $user->id;
        $downlineIds = User::getDownlineIds($rootId);
        array_push($downlineIds, $user->id);
        array_push($downlineIds, $user->root_id);
        return self::query()->orderBy('id','asc')->whereRaw('id IN ('.getDownlinesStr(implode(',', array_filter($downlineIds))).')')->pluck('user_name', 'id');
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * Get user name
     *
     * @param string $token
     * @return void
     */
    public function getUserNameAttribute() {
        return $this->email . '('.ucfirst($this->name) . ' ' . ucfirst($this->last_name) . ')';
    }

	/**
	 * Tasks
	 */
	public function tasks() {
		return $this->hasMany(Task::class);
	}

    /**
     * Trello statuses
     */
    public function trelloStatuses() {
        return $this->hasMany(TrelloStatus::class);
    }

    /**
     * Trello boards
     */
    public function trelloBoards() {
        return $this->belongsToMany(TrelloBoard::class, 'user_trello_boards')->withTimestamps();
    }
}
