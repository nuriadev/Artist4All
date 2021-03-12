import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { User } from '../models/user';

@Injectable()
export class UserService {
  constructor(private conexHttp:HttpClient) { }

  register(newUser:User):Observable<any> {
    let url = "http://localhost/daw2/Artist4all/artist4all_php/User/register.php";
    //let url = "http://localhost:8888/daw2/Artist4all/artist4all_php/User/register.php";

    let registerFormData:FormData = new FormData();
    registerFormData.append('name', newUser.name);
    registerFormData.append('surname1', newUser.surname1);
    registerFormData.append('surname2', newUser.surname2);
    registerFormData.append('email',newUser.email);
    registerFormData.append('username', newUser.username);
    registerFormData.append('password',newUser.password);
    registerFormData.append('type_user',""+newUser.type_user);
    registerFormData.append('n_followers',""+newUser.n_followers);
    registerFormData.append('img',newUser.img);

    return this.conexHttp.post(url, registerFormData);
  }


}