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
    registerFormData.append('isArtist',''+newUser.isArtist);
    registerFormData.append('imgAvatar', 'http://localhost:81/assets/img/defaultAvatarImg.png');
    registerFormData.append('aboutMe', 'Bienvenido a mi perfil!!!');

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
      editFormData.append('id',''+id);
      editFormData.append('name', name);
      editFormData.append('surname1', surname1);
      editFormData.append('surname2', surname2);
      editFormData.append('email', email);
      editFormData.append('username', username);
      editFormData.append('aboutMe', aboutMe);
      if (!files) editFormData.append('newImgAvatar', null);
      else editFormData.append('newImgAvatar', files[0],files[0].name);
      editFormData.append('token', token);
 //    TODO Cambiar a patch
    return this.http.post(this.url + '/settings/profile', editFormData);
  }

  editPassword(
    id:number,
    password:string,
    token:string):Observable<any> {
      let editPasswordFormData:FormData = new FormData();
      editPasswordFormData.append('id',''+id);
      editPasswordFormData.append('password', password);
      editPasswordFormData.append('token', token);
   //    TODO Cambiar a patch
      return this.http.post(this.url + '/settings/password', editPasswordFormData);
  }

  getOtherUsers(username:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/list');
  }

  getUserByUsername(username:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username);
  }

  isFollowingThatUser(
    username_follower:string,
    username_followed:string,
    token:string):Observable<any> {
    return this.http.get(
      this.url + '/user/' + username_follower + '/follow/' + username_followed,
      { headers: new HttpHeaders({ 'Authorization': token })}
    );
  }

  followUser(
    username_follower:string,
    username_followed:string,
    id_follower:number,
    id_followed:number,
    token:string):Observable<any> {
    let followUserFormData:FormData = new FormData();
    followUserFormData.append('id_follower',''+id_follower);
    followUserFormData.append('id_followed',''+id_followed);
    followUserFormData.append('token', token);
    return this.http.post(
      this.url + '/user/' + username_follower + '/follow/' + username_followed,
      followUserFormData
    );
  }

  unfollowUser(
    username_follower:string,
    username_followed:string,
    id_follow:number,
    token:string):Observable<any> {
    let options = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json', 'Authorization': token }),
      body: { id: id_follow }
    };
    return this.http.delete(
      this.url + '/user/' + username_follower + '/follow/' + username_followed,
      options
    );
  }

  countFollowers(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/followers', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  countFollowed(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username +'/followed', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  getFollowers(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/list/followers', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  getUsersFollowed(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/list/followed', { headers: new HttpHeaders({ 'Authorization': token })});
  }
}
