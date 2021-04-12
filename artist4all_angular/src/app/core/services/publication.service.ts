import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Publication } from '../models/publication';

@Injectable({
  providedIn: 'root'
})
export class PublicationService {

  constructor(private http:HttpClient) { }

  private url = 'http://localhost:81/user';

  create(newPublication:Publication, token:string):Observable<any> {
    let createPublicationFormData:FormData = new FormData();
    createPublicationFormData.append('id_user',''+newPublication.id_user);
    if (!newPublication.imgsPublication) {
      createPublicationFormData.append('imgsPublication', null);
    } else {
      let imgsPublication = [];
      for (var i = 0; i < newPublication.imgsPublication.length; i++) {
        imgsPublication[i] = newPublication.imgsPublication[i].name;
      }
      createPublicationFormData.append('imgsPublication', JSON.stringify(imgsPublication));
    }
    createPublicationFormData.append('bodyPublication', newPublication.bodyPublication);
    createPublicationFormData.append('upload_date',''+newPublication.upload_date);
    createPublicationFormData.append('n_likes',''+newPublication.n_likes);
    createPublicationFormData.append('n_comments',''+newPublication.n_comments);
    createPublicationFormData.append('token', token);

    return this.http.post(this.url + '/my/publication', createPublicationFormData);
  }

  getUserPublications(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/' + username + '/publication', { headers: new HttpHeaders({ 'Authorization': token })});
  }

  getPublicationById(id:number, username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/' + username + '/publication/' + id, { headers: new HttpHeaders({ 'Authorization': token })});
  }

  edit(publication:Publication, token:string):Observable<any> {
    let editPublicationFormData:FormData = new FormData();
    if (!publication.imgsPublication) {
      editPublicationFormData.append('imgsPublication', null);
    } else {
      let imgsPublication = [];
      for (var i = 0; i < publication.imgsPublication.length; i++) {
        imgsPublication[i] = publication.imgsPublication[i].name;
      }
      editPublicationFormData.append('imgsPublication', JSON.stringify(imgsPublication));
    }
    editPublicationFormData.append('bodyPublication', publication.bodyPublication);
    editPublicationFormData.append('token', token);

    // TODO: pasar a patch
    return this.http.post(this.url + '/my/publication/' + publication.id, editPublicationFormData);
  }

  delete(id:number, token:string):Observable<any> {
    return this.http.delete(this.url + '/my/publication/' + id, { headers: new HttpHeaders({ 'Authorization': token })});
  }

  addLike(publication:Publication, my_id:number, publisher_username:string, token:string):Observable<any> {
    let likeFormData:FormData = new FormData;
    likeFormData.append('my_id',''+my_id);
    return this.http.post(this.url + '/' + publisher_username + '/publication/' + publication.id + '/like', likeFormData, { headers: new HttpHeaders({ 'Authorization': token })});
  }

  removelike(id_publication:Publication, publisher_username:string, token:string):Observable<any> {
    let options = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json', 'Authorization': token }),
      body: { id_publication: id_publication }
    };
    return this.http.delete(
      this.url + '/' + publisher_username + '/publication/' + id_publication + '/like',
      options
    );
  }
}
