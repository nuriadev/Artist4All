import { Publication } from './publication';
import { User } from './user';

export class Comment {
  id: number;
  user: User;
  bodyComment: string;
  isEdited: number;
  comment_date: Date;
  id_comment_reference: number;

  constructor(
    id: number,
    user: User,
    bodyComment: string,
    isEdited: number,
    comment_date: Date,
    id_comment_reference: number
  ) {
    this.id = id;
    this.user = user;
    this.bodyComment = bodyComment;
    this.isEdited = isEdited;
    this.comment_date = comment_date;
    this.id_comment_reference = id_comment_reference;
  }
}
