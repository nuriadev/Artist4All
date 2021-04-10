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
    registerFormData.append('isPrivate',''+newUser.isPrivate);

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
    return this.http.post(this.url + '/user/my/settings/profile', editFormData);
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
      return this.http.post(this.url + '/user/my/settings/password', editPasswordFormData);
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

  requestOrFollowUser(
    id_follow:number,
    username_follower:string,
    username_followed:string,
    status_follow:number,
    token:string):Observable<any> {
    let requestOrFollowUserFormData:FormData = new FormData();
    if (id_follow != null) requestOrFollowUserFormData.append('id',''+id_follow);
    requestOrFollowUserFormData.append('status_follow',''+status_follow);
    return this.http.post(
      this.url + '/user/' + username_follower + '/follow/' + username_followed,
      requestOrFollowUserFormData,
      { headers: new HttpHeaders({ 'Authorization': token }) }
    );
  }

  cancelRequestOrUnfollowUser(
    id_follow:number,
    username_follower:string,
    username_followed:string,
    status_follow:number,
    token:string):Observable<any> {
      let cancelRequestOrUnfollowFormData:FormData = new FormData();
      cancelRequestOrUnfollowFormData.append('status_follow',''+status_follow);
      //TODO cambiar a patch y usar la ruta de requestOrFollowUser
      return this.http.post(
        this.url + '/user/' + username_follower + '/follow/' + username_followed + '/' + id_follow,
        cancelRequestOrUnfollowFormData,
        { headers: new HttpHeaders({ 'Authorization': token }) }
      );
/*     let options = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json', 'Authorization': token }),
      body: { id_follow: id_follow }
    };
    return this.http.delete(
      this.url + '/user/' + username_follower + '/follow/' + username_followed,
      options
    ); */
  }

  countFollowers(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/follower', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  countFollowed(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username +'/followed', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  getFollowers(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/list/follower', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  getUsersFollowed(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/list/followed', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  switchPrivateAccount(user:User, token:string):Observable<any> {
    let switchPrivateAccountFormData:FormData = new FormData();
    switchPrivateAccountFormData.append('username', user.username);
    switchPrivateAccountFormData.append('isPrivate',''+user.isPrivate);
    switchPrivateAccountFormData.append('token', token);
    // TODO: pasar a patch
    return this.http.post(this.url + '/user/my/settings/account/privacy', switchPrivateAccountFormData);
  }


}
