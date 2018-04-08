<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\User\Customer;
 
 use models\Entities\User\Customer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\UserRepository")
  */
 class Personal extends Customer {
 	
	/** @Column(type="string", length=60, nullable=false) */
	private $first_name;
	/** @Column(type="string", length=60, nullable=false) */
	private $last_name;
	/** @Column(type="string", length=60, nullable=true) */
	private $nickname;
	/** @Column(type="string", length=60, nullable=true) */
	private $avatar;
    /** @Column(type="smallint", length=2, nullable=true) */
    private $profile_public;
    /** @Column(type="smallint", length=2, nullable=true) */
    private $profile_history;
    /** @Column(type="smallint", length=2, nullable=true) */
    private $profile_wishlist;
    /** @Column(type="smallint", length=2, nullable=true) */
    private $profile_friends;
    /** @Column(type="integer", length=10, nullable=false) */
    private $points; 
    /** @Column(type="date") */
    protected $date_of_birth;
	
	
	 /**
     * @ManyToMany(targetEntity="Personal", mappedBy="userFriends")
     **/
    private $friendsWithUser;

    /**
     * @ManyToMany(targetEntity="Personal", inversedBy="friendsWithUser")
     * @JoinTable(name="ecom_users_friends",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="friend_id", referencedColumnName="id")}
     *      )
     **/
    private $userFriends;
	
	/**
     * @OneToMany(targetEntity="models\Entities\OrderFinal", mappedBy="user")
     */
    private $orders_final;
	
	/**
     * @OneToMany(targetEntity="models\Entities\OrderShopFinal", mappedBy="user")
     */
    private $orders_shop_final;
	
	public function __construct() {

        $this->orders_final			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders_shop_final	= new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	public function setFirstName( $value ) { $this->first_name = $value; }
    public function getFirstName() { return $this->first_name; }
	public function setLastName( $value ) { $this->last_name = $value; }
    public function getLastName() { return $this->last_name; }
    public function setNickname( $value ) { $this->nickname = $value; }
    public function getNickname() { return $this->nickname; }
    public function setDateOfBirth ( $value ) { $this->date_of_birth = $value; }
    public function getDateOfBirth() { return $this->date_of_birth; }
    public function getFormatedDateOfBirth() { if( $this->date_of_birth != null ) { return $this->date_of_birth->format('d.m.Y'); } else { return ''; } }
	public function setAvatar( $value ) { $this->avatar = $value; }
    public function getAvatar() { return $this->avatar; }
    public function getAvatarURL() { return assets_url( 'img/users/large/'.($this->avatar ? $this->avatar : 'avatar.png') ); }
    public function setProfilePublic( $value ) { $this->profile_public = $value; }
    public function getProfilePublic() { return $this->profile_public; }
    public function setProfileHistory( $value ) { $this->profile_history = $value; }
    public function getProfileHistory() { return $this->profile_history; }
    public function setProfileWishlist( $value ) { $this->profile_wishlist = $value; }
    public function getProfileWishlist() { return $this->profile_wishlist; }
    public function setProfileFriends( $value ) { $this->profile_friends = $value; }
    public function getProfileFriends() { return $this->profile_friends; } 
    public function getPoints() { return $this->points; } 
	
	public function getUserFriends() { return $this->userFriends; }

	public function getFriendsWithUser() { return $this->friendsWithUser; }
	
	public function getFriends() {
		$this->allFriends = array();
		foreach ($this->userFriends as $friend) if(!in_array($friend, $this->allFriends)) $this->allFriends[] = $friend;
		foreach ($this->friendsWithUser as $friend) if(!in_array($friend, $this->allFriends)) $this->allFriends[] = $friend;
		
		return $this->allFriends;
	}
    
 	public function setPoints( $value ) {
 		$this->points += $value;
 		if( $this->points > 500 ) $this->points = 500;
 	}
 	
 	public function removePoints( $value ) {
 		$this->points -= $value;
 		if( $this->points < 0 ) $this->points = 0;
 	}
 }
 
 /* End of file Personal.php */
 /* Location: ./system/applications/_backend/models/Entities/User/Customer/Personal.php */