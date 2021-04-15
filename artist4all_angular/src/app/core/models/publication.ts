import { Comment } from './comment';
export class Publication {
  id: number;
  id_user: number;
  imgsPublication: FileList;
  bodyPublication: string;
  upload_date: Date;
  likes: any[];
  comments:Array<Comment>;
  // todo  comentarios arrayPara los comentarios de las publicaciones

  constructor(
    id: number,
    id_user: number,
    imgsPublication: FileList,
    bodyPublication: string,
    upload_date: Date,
    likes: any[],
    comments:Array<Comment>
  ) {
    this.id = id;
    this.id_user = id_user;
    this.imgsPublication = imgsPublication;
    this.bodyPublication = bodyPublication;
    this.upload_date = upload_date;
    this.likes = likes;
    this.comments = comments;
  }
}
