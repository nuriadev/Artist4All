<?php

class User {

  private ?int $id;
  private string $name;
  private string $surname1;
  private string $surname2;
  private string $email;
  private string $username;
  private string $password;
  private int $n_followers;
  private int $type_user;

  public function __construct(
    ?int $id,
    string $name,
    string $surname1,
    string $surname2,
    string $email,
    string $username,
    string $password,
    int $n_followers = 0,
    int $type_user) {
      $this->id = $id;
      $this->name = $name;
      $this->surname1 = $surname1;
      $this->surname2 = $surname2;
      $this->email = $email;
      $this->username = $username;
      $this->password = $password;
      $this->n_followers = $n_followers;
      $this->type_user = $type_user;
    }

    public function getId() : ?int {
      return $this->id;
    }

    public function setId(?int $id) {
      $this->$id = $id;
    }

    public function getName() : string {
      return $this->name;
    }

    public function setName(string $name) {
      $this->name = $name;
    }

    public function getSurname1() : string {
      return $this->surname1;
    }

    public function setSurname1(string $surname1) {
      $this->surname1 = $surname1;
    }

    public function getSurname2() : string {
      return $this->surname2;
    }

    public function setSurname2(string $surname2) {
      $this->surname2 = $surname2;
    }

    public function getEmail() : string {
      return $this->email;
    }

    public function setEmail(string $email) {
      $this->email = $email;
    }

    public function getUsername() : string {
      return $this->username;
    }

    public function setUsername(string $username) {
      $this->username = $username;
    }

    public function getPassword() : string {
      return $this->password;
    }

    public function setPassword(string $password) {
      $this->password = $password;
    }

    public function getN_followers() {
      return $this->n_followers;
    }

    public function setN_followers(int $n_followers) {
      $this->n_followers = $n_followers;
    }

    public function getType_user() {
      return $this->type_user;
    }

    public function setType_artist(int $type_user) {
      $this->type_user = $type_user;
    }

}