import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Publication } from '../models/publication';

@Injectable({
  providedIn: 'root'
})
export class PublicationService {

  constructor(private http:HttpClient) { }

  private url = 'http://localhost:81/publication';

  create(newPublication:Publication, token:string):Observable<any> {
    let createPublicationFormData:FormData = new FormData();
    createPublicationFormData.append('id_user',''+newPublication.id_user);
    createPublicationFormData.append('bodyPublication', newPublication.bodyPublication);
    createPublicationFormData.append('upload_date',''+newPublication.upload_date);
    createPublicationFormData.append('n_likes',''+newPublication.n_likes);
    createPublicationFormData.append('n_comments',''+newPublication.n_comments);
    createPublicationFormData.append('n_views',''+newPublication.n_views);
    createPublicationFormData.append('token', token);

    return this.http.post(this.url, createPublicationFormData);
  }
}
