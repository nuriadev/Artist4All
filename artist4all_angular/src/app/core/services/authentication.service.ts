import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { LoginUser } from '../models/loginUser';

@Injectable()
export class AuthenticationService {
  constructor(private http:HttpClient) { }

  //private url = 'http://localhost/daw2/Artist4all/artist4all_php/User';
  private url = 'http://localhost:81';

  login(user:LoginUser):Observable<any> {
    let loginForm = new HttpParams();
    loginForm.set('email', user.email);
    loginForm.set('password', user.password);

    return this.http.post(
      this.url + '/login',
      loginForm.toString(),
      { headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' }) }
    );
  }

  logout(token:string):Observable<any> {
    let logoutForm = new HttpParams();
    logoutForm.set('token',token);

    return this.http.post(
      this.url + '/logout',
      logoutForm.toString(),
      { headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' }) }
    );
  }

}
