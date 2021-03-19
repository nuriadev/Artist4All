import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { User } from '../models/user';

@Injectable()
export class UserService {
  constructor(private http:HttpClient) { }

  //let url = "http://localhost/daw2/Artist4all/artist4all_php/User/register.php";
  private url = "http://localhost:81";

  register(newUser:User):Observable<any> {
    let registerForm = new HttpParams();
    registerForm.set('name', newUser.name);
    registerForm.set('surname1', newUser.surname1);
    registerForm.set('surname2', newUser.surname2);
    registerForm.set('email', newUser.email);
    registerForm.set('username', newUser.username);
    registerForm.set('password', newUser.password);
    registerForm.set('type_user',""+newUser.type_user);
    registerForm.set('n_followers',""+newUser.n_followers);

    return this.http.post(
      this.url + '/register',
      registerForm.toString(),
      { headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })}
    );
  }

  editSimple(
    name:string,
    surname1:string,
    surname2:string,
    email:string,
    aboutMe:string,
    token:string):Observable<any> {
      let editSimpleFormData:FormData = new FormData();
      editSimpleFormData.append('name', name);
      editSimpleFormData.append('surname1', surname1);
      editSimpleFormData.append('surname2', surname2);
      editSimpleFormData.append('email', email);
      editSimpleFormData.append('aboutMe', aboutMe);
      editSimpleFormData.append('token', token);

      return this.http.post(this.url + '/editSimple.php', editSimpleFormData);
    }

  edit(
    username:string,
    aboutMe:string,
    files:FileList,
    name:string,
    email:string,
    surname1:string,
    surname2:string,
    token:string):Observable<any> {
      let editFormData:FormData = new FormData();
      editFormData.append('username', username);
      editFormData.append('aboutMe', aboutMe);
      editFormData.append('img',files[0],files[0].name);
      editFormData.append('name', name);
      editFormData.append('email', email);
      editFormData.append('surname1', surname1);
      editFormData.append('surname2', surname2);
      editFormData.append('token', token);

    return this.http.post(this.url + '/edit.php', editFormData);
  }

  editPassword(
    password:string,
    token:string):Observable<any> {
      let editPasswordFormData:FormData = new FormData();
      editPasswordFormData.append('password', password);
      editPasswordFormData.append('token', token);

      return this.http.post(this.url + '/editPassword.php', editPasswordFormData);
  }
}
