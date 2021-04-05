
export class Publication {
  id:number;
  id_user:number;
  imgPublication:FileList;
  bodyPublication:string;
  upload_date:Date;
  n_likes:number;
  n_comments:number;
  n_views:number;
  // todo  comentarios arrayPara los comentarios de las publicaciones


  constructor(
    id:number,
    id_user:number,
    imgPublication:FileList,
    bodyPublication:string,
    upload_date:Date,
    n_likes:number,
    n_comments:number,
    n_views:number) {
      this.id = id;
      this.id_user = id_user;
      this.imgPublication = imgPublication;
      this.bodyPublication = bodyPublication;
      this.upload_date = upload_date;
      this.n_likes = n_likes;
      this.n_comments = n_comments;
      this.n_views = n_views;
  }

}
