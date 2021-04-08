
export class Publication {
  id:number;
  id_user:number;
  imgsPublication:FileList;
  bodyPublication:string;
  upload_date:Date;
  n_likes:number;
  n_comments:number;
  // todo  comentarios arrayPara los comentarios de las publicaciones


  constructor(
    id:number,
    id_user:number,
    imgsPublication:FileList,
    bodyPublication:string,
    upload_date:Date,
    n_likes:number,
    n_comments:number) {
      this.id = id;
      this.id_user = id_user;
      this.imgsPublication = imgsPublication;
      this.bodyPublication = bodyPublication;
      this.upload_date = upload_date;
      this.n_likes = n_likes;
      this.n_comments = n_comments;
  }

}
