import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { User } from '../models/user';
import { Session } from '../models/session';

@Injectable()
export class UserService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81';

  register(newUser: User): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('name', newUser.name);
    newForm.append('surname1', newUser.surname1);
    newForm.append('surname2', newUser.surname2);
    newForm.append('email', newUser.email);
    newForm.append('username', newUser.username);
    newForm.append('password', newUser.password);
    newForm.append('isArtist', '' + newUser.isArtist);
    newForm.append(
      'imgAvatar',
      'http://localhost:81/assets/img/defaultAvatarImg.png'
    );
    newForm.append('aboutMe', 'Bienvenido a mi perfil!!!');
    newForm.append('isPrivate', '' + newUser.isPrivate);

    return this.http.post(this.url + '/register', newForm);
  }

  edit(
    id: number,
    name: string,
    surname1: string,
    surname2: string,
    email: string,
    username: string,
    aboutMe: string,
    files: FileList,
    token: string
  ): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id', '' + id);
    newForm.append('name', name);
    newForm.append('surname1', surname1);
    newForm.append('surname2', surname2);
    newForm.append('email', email);
    newForm.append('username', username);
    newForm.append('aboutMe', aboutMe);
    if (!files) newForm.append('newImgAvatar', null);
    else newForm.append('newImgAvatar', files[0], files[0].name);
    //    TODO Cambiar a patch
    return this.http.patch(this.url + '/user/' + id + '/profile', newForm, { headers: new HttpHeaders({ Authorization: token }) });
  }

  changePassword(id: number, formValues, token: string): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id', '' + id);
    newForm.append('password', formValues.password);
    //    TODO Cambiar a patch
    return this.http.post(this.url + '/user/' + id + '/password', newForm, { headers: new HttpHeaders({ Authorization: token }) });
  }

  getAllOtherUsers(id: number, token: string): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/list', { headers: new HttpHeaders({ Authorization: token }) });
  }

  getUserById(id: number, token: string): Observable<any> {
    return this.http.get(this.url + '/user/' + id, { headers: new HttpHeaders({ Authorization: token }) });
  }

  isFollowingThatUser(id_follower: number, id_followed: number, token: string): Observable<any> {
    return this.http.get(this.url + '/user/' + id_follower + '/follow/' + id_followed, { headers: new HttpHeaders({ Authorization: token }) });
  }

  requestOrFollowUser(
    id_follow: number,
    id_follower: number,
    id_followed: number,
    status_follow: number,
    token: string
  ): Observable<any> {
    let newForm: FormData = new FormData();
    if (id_follow != null) newForm.append('id_follow', '' + id_follow);
    newForm.append('status_follow', '' + status_follow);
    return this.http.post(this.url + '/user/' + id_follower + '/follow/' + id_followed, newForm, { headers: new HttpHeaders({ Authorization: token }) });
  }

  updateFollowRequest(
    id_follow: number,
    id_follower: number,
    id_followed: number,
    status_follow: number,
    token: string
  ): Observable<any> {
    let newForm: FormData = new FormData();
    /*       cancelRequestOrUnfollowFormData.append('id_follow',''+id_follow); */
    newForm.append('status_follow', '' + status_follow);
    //TODO cambiar a patch y usar la ruta de requestOrFollowUser
    return this.http.post(this.url + '/user/' + id_follower + '/follow/' + id_followed + '/' + id_follow, newForm, { headers: new HttpHeaders({ Authorization: token }) });
    /*     let options = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json', 'Authorization': token }),
      body: { id_follow: id_follow }
    };
    return this.http.delete(
      this.url + '/user/' + username_follower + '/follow/' + username_followed,
      options
    ); */
  }

  getFollowers(id: number, token: string): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/follower', { headers: new HttpHeaders({ Authorization: token }) });
  }

  getFollowed(id: number, token: string): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/followed', { headers: new HttpHeaders({ Authorization: token }) });
  }

  privateAccountSwitcher(user: User, token: string): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('isPrivate', '' + user.isPrivate);
    // TODO: pasar a patch
    return this.http.post(this.url + '/user/' + user.id + '/settings/account/privacy',  newForm, { headers: new HttpHeaders({ Authorization: token }) });
  }
}
