import { AppModule } from "../app.module";

export class User {
  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  password:string = "";
  type_user:number;

  constructor(
    name:string,
    surname1:string,
    surname2:string,
    email:string,
    username:string,
    password:string,
    type_user:number) {
    this.name = name;
    this.surname1 = surname1;
    this.surname2 = surname2;
    this.email = email;
    this.username = username;
    this.password = password;
    this.type_user = type_user
  }

}
