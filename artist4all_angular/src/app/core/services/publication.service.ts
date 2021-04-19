import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Publication } from '../models/publication';

@Injectable({
  providedIn: 'root',
})
export class PublicationService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81/user/';

  create(newPublication: Publication): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id_user', '' + newPublication.user.id);
    if (newPublication.imgsPublication !== null) {
      for (var i = 0; i < newPublication.imgsPublication.length; i++) {
        let file = newPublication.imgsPublication.item(i);
        newForm.append('imgPublication'+i, file, file.name);
      }
    }
    newForm.append('bodyPublication', newPublication.bodyPublication);
    newForm.append('upload_date', '' + newPublication.upload_date);
    newForm.append('n_likes', '' + newPublication.n_likes);
    newForm.append('n_comments', '' + newPublication.n_comments);
    newForm.append('isLiking', '' + newPublication.isLiking);
    newForm.append('isEdited', '' + newPublication.isEdited);
    return this.http.post(this.url + newPublication.user.id + '/publication', newForm);
  }

  getUserPublications(id_user: number): Observable<any> {
    return this.http.get(this.url + id_user + '/publication');
  }

  getPublicationById(id_user: number, id_publication: number): Observable<any> {
    return this.http.get(this.url + id_user + '/publication/' + id_publication);
  }

  edit(publication: Publication): Observable<any> {
    let newForm: FormData = new FormData();
    Array.from(publication.imgsPublication).forEach(img => {
      if (publication.imgsPublication !== null) {
        for (var i = 0; i < publication.imgsPublication.length; i++) {
          let file = publication.imgsPublication.item(i);
          newForm.append('imgPublication'+i, file, file.name);
        }
      }
    });
    newForm.append('isEdited', '' + publication.isEdited);
    newForm.append('bodyPublication', publication.bodyPublication);
    // TODO: pasar a patch
    return this.http.post(this.url + publication.user.id + '/publication/' + publication.id, newForm);
  }

  delete(id_user: number, id_publication: number): Observable<any> {
    return this.http.delete(this.url + id_user + '/publication/' + id_publication);
  }


  likePublication(publication: Publication, my_id: number): Observable<any> {
    let newForm: FormData = new FormData();
    return this.http.post(this.url + my_id + '/like/publication/' + publication.id, newForm);
  }

  updateLikeStatus(publication: Publication, my_id: number): Observable<any> {
    let newForm: FormData = new FormData();
    return this.http.post(this.url + my_id + '/like/publication/' + publication.id + '/update' , newForm);
    /*  let options = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        Authorization: token,
      }),
      body: { publication: publication.id_user },
    };
    return this.http.delete(
      this.url + my_id + '/like/publication' + publication.id,
      options
    ); */
  }
}
