import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { LoginUser } from '../models/loginUser';

@Injectable()
export class AuthenticationService {

  constructor(private conexHttp:HttpClient) { }

  private url = 'http://localhost/daw2/Artist4all/artist4all_php/User';
  //private url = 'http://localhost:8888/daw2/Artist4all/artist4all_php/User';

  login(user:LoginUser):Observable<any> {
    let loginFormData:FormData = new FormData();
    loginFormData.append('email',user.email);
    loginFormData.append('password',user.password);

    return this.conexHttp.post(this.url + '/login.php', loginFormData);
  }

  logout(token:string):Observable<any> {
    let logoutFormData:FormData = new FormData();
    logoutFormData.append('token',token);

    return this.conexHttp.post(this.url + '/logout.php', logoutFormData);
  }

}
