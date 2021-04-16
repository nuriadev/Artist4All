import { Comment } from './comment';
import { User } from './user';
export class Publication {
  id: number;
  user: User;
  imgsPublication: FileList;
  bodyPublication: string;
  upload_date: Date;
  n_likes: number;
  n_comments: number;
  isLiking: number;

  constructor(
    id: number,
    user: User,
    imgsPublication: FileList,
    bodyPublication: string,
    upload_date: Date,
    n_likes: number,
    n_comments: number,
    isLiking: number
  ) {
    this.id = id;
    this.user = user;
    this.imgsPublication = imgsPublication;
    this.bodyPublication = bodyPublication;
    this.upload_date = upload_date;
    this.n_likes = n_likes;
    this.n_comments = n_comments;
    this.isLiking = isLiking;
  }
}
