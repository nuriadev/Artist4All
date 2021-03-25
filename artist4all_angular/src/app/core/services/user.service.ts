import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { User } from '../models/user';

@Injectable()
export class UserService {
  constructor(private http:HttpClient) { }

  private url = "http://localhost:81";

  register(newUser:User):Observable<any> {
    let registerFormData:FormData = new FormData();
    registerFormData.append('name', newUser.name);
    registerFormData.append('surname1', newUser.surname1);
    registerFormData.append('surname2', newUser.surname2);
    registerFormData.append('email', newUser.email);
    registerFormData.append('username', newUser.username);
    registerFormData.append('password', newUser.password);
    registerFormData.append('isArtist',""+newUser.isArtist);
    registerFormData.append('imgAvatar', "http://localhost:81/assets/img/imgUnknown.png");
    registerFormData.append('aboutMe', "Bienvenido a mi perfil!!!");

    return this.http.post(this.url + '/register', registerFormData);
  }

  edit(
    id:number,
    name:string,
    surname1:string,
    surname2:string,
    email:string,
    username:string,
    aboutMe:string,
    files:FileList,
    token:string):Observable<any> {
      let editFormData:FormData = new FormData();
      editFormData.append('id',""+id);
      editFormData.append('name', name);
      editFormData.append('surname1', surname1);
      editFormData.append('surname2', surname2);
      editFormData.append('email', email);
      editFormData.append('username', username);
      editFormData.append('aboutMe', aboutMe);
      editFormData.append('newImgAvatar', files[0],files[0].name);
      editFormData.append('token', token);

    return this.http.put(this.url + '/edit', editFormData);
  }

  editPassword(
    id:number,
    password:string,
    token:string):Observable<any> {
      let editPasswordFormData:FormData = new FormData();
      editPasswordFormData.append('id',""+id);
      editPasswordFormData.append('password', password);
      editPasswordFormData.append('token', token);

      return this.http.patch(this.url + '/editPassword', editPasswordFormData);
  }

  getOtherUsers(username:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username);
  }

  getUserByUsername(username:string):Observable<any> {
    return this.http.get(this.url + '/profile/' + username);
  }
}
