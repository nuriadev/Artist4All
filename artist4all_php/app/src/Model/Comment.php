<?php

namespace Artist4all\Model;

class Comment implements \JsonSerializable
{
  private ?int $id;
  private \Artist4all\Model\User $user;
  private string $bodyComment;
  private int $isEdited;
  private string $comment_date;
  private ?int $id_comment_reference;


  public function __construct(
    ?int $id,
    \Artist4all\Model\User $user,
    string $bodyComment,
    int $isEdited,
    string $comment_date,
    ?int $id_comment_reference
  ) {
    $this->id = $id;
    $this->user = $user;
    $this->bodyComment = $bodyComment;
    $this->isEdited = $isEdited;
    $this->comment_date = $comment_date;
    $this->id_comment_reference = $id_comment_reference;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id)
  {
    $this->id = $id;
  }

  public function getUser(): \Artist4all\Model\User
  {
    return $this->user;
  }

  public function setUser(\Artist4all\Model\User $user)
  {
    $this->user = $user;
  }

  public function getBodyComment(): ?string
  {
    return $this->bodyComment;
  }

  public function setBodyComment(?string $bodyComment)
  {
    $this->bodyComment = $bodyComment;
  }

  public function isEdited(): int
  {
    return $this->isEdited;
  }

  public function setIsEdited(int $isEdited)
  {
    $this->isEdited = $isEdited;
  }

  public function getCommentDate(): string
  {
    return $this->comment_date;
  }

  public function setCommentDate(string $comment_date)
  {
    $this->comment_date = $comment_date;
  }

  public function getIdCommentReference(): ?int
  {
    return $this->id_comment_reference;
  }

  public function setIdCommentReference(?int $id_comment_reference)
  {
    $this->id_comment_reference = $id_comment_reference;
  }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\Comment
  {
    return new \Artist4all\Model\Comment(
      $data['id'],
      $data['user'],
      $data['bodyComment'],
      $data['isEdited'],
      $data['comment_date'],
      $data['id_comment_reference']
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize()
  {
    return [
      'id' => $this->id,
      'user' => $this->user,
      'bodyComment' => $this->bodyComment,
      'isEdited' => $this->isEdited,
      'comment_date' => $this->comment_date,
      'id_comment_reference' => $this->id_comment_reference
    ];
  }
}