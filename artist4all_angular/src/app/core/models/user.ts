
export class User {
  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  password:string = "";
  isArtist:number;
  n_followers:number;
  imgAvatar:FileList;
  aboutMe:string = "";

  constructor(
    name:string,
    surname1:string,
    surname2:string,
    email:string,
    username:string,
    password:string,
    isArtist:number,
    n_followers:number,
    imgAvatar:FileList,
    aboutMe:string) {
      this.name = name;
      this.surname1 = surname1;
      this.surname2 = surname2;
      this.email = email;
      this.username = username;
      this.password = password;
      this.isArtist = isArtist;
      this.n_followers = n_followers;
      this.imgAvatar = imgAvatar;
      this.aboutMe = aboutMe;
  }

}
