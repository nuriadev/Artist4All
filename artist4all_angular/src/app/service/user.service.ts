import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

@Injectable()
export class UserService {
  constructor(private conexHttp:HttpClient) { }

  register(email:string,password:string):Observable<any> {
    let url = "http://localhost/daw2/Artist4all/artist4all_php/User/register.php";

    let registerFormData:FormData = new FormData();
    registerFormData.append('email',email);
    registerFormData.append('password',password);

    return this.conexHttp.post(url, registerFormData);
  }

  login(email:string,password:string):Observable<any> {
    let url = "http://localhost/daw2/Artist4all/artist4all_php/User/login.php";

    let loginFormData:FormData = new FormData();
    loginFormData.append('email',email);
    loginFormData.append('password',password);

    return this.conexHttp.post(url, loginFormData);
  }

}
