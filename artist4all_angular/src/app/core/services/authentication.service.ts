import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { LoginUser } from '../models/loginUser';

@Injectable()
export class AuthenticationService {
  constructor(private http:HttpClient) { }

  private url = 'http://localhost:81';

  login(user:LoginUser):Observable<any> {
    let loginFormData:FormData = new FormData();
    loginFormData.append('email', user.email);
    loginFormData.append('password', user.password);

    return this.http.post(this.url + '/login', loginFormData);
  }

  logout(token:string):Observable<any> {
    let logoutFormData:FormData = new FormData();
    logoutFormData.append('token',token);

    return this.http.post(this.url + '/logout', logoutFormData);
  }

}
