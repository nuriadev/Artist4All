import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { LoginUser } from '../models/loginUser';

@Injectable()
export class AuthenticationService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81';

  login(user: LoginUser): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('email', user.email);
    newForm.append('password', user.password);

    return this.http.post(this.url + '/login', newForm);
  }

  logout(token: string): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('token', token);

    return this.http.post(this.url + '/logout', newForm);
  }
}
