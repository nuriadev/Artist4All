export class LoginUser {

  public email: string;
  public password: string;

  constructor(email: string, password: string){
    this.email = email;
    this.password = password;
  }
}