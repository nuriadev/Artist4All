
export class User {
  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  password:string = "";
  type_user:number;
  n_followers:number;
  img:string = "";

  constructor(
    name:string,
    surname1:string,
    surname2:string,
    email:string,
    username:string,
    password:string,
    type_user:number,
    n_followers:number,
    img:string) {
      this.name = name;
      this.surname1 = surname1;
      this.surname2 = surname2;
      this.email = email;
      this.username = username;
      this.password = password;
      this.type_user = type_user;
      this.n_followers = n_followers;
      this.img = img;
  }

}
