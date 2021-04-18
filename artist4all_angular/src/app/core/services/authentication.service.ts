import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { LoginUser } from '../models/loginUser';
import { User } from '../models/user';

@Injectable()
export class AuthenticationService {
  constructor(private http: HttpClient) { }

  private url = 'http://localhost:81';

  login(user: LoginUser): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('email', user.email);
    newForm.append('password', user.password);

    return this.http.post(this.url + '/login', newForm);
  }

  logout(id_user: number): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id', '' + id_user);
    return this.http.post(this.url + '/logout', newForm);
  }

}
