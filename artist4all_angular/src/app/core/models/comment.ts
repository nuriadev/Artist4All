import { User } from './user';

export class Comment {
  id: number;
  user: User;
  bodyComment: string;
  isEdited: number;
  comment_date: Date;
  id_publication: number;
  id_comment_reference: number;
  user_reference: User;

  constructor(
    id: number,
    user: User,
    bodyComment: string,
    isEdited: number,
    comment_date: Date,
    id_publication: number,
    id_comment_reference: number,
    user_reference: User
  ) {
    this.id = id;
    this.user = user;
    this.bodyComment = bodyComment;
    this.isEdited = isEdited;
    this.comment_date = comment_date;
    this.id_publication = id_publication;
    this.id_comment_reference = id_comment_reference;
    this.user_reference = user_reference;
  }
}
