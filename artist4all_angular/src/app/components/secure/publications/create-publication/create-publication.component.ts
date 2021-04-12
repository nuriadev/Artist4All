import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';

@Component({
  selector: 'app-create-publication',
  templateUrl: './create-publication.component.html',
  styleUrls: ['./create-publication.component.css']
})
export class CreatePublicationComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _publicationService: PublicationService,
    private _router: Router
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  ngOnInit(): void { }

  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
      this.imgToUpload = imgPublication;
  }

  bodyPublication:string = "";
  createPublication()   {
    this._publicationService.create(
      new Publication(null, this.user.id, this.imgToUpload, this.bodyPublication, null, 0, 0),
      this.token).subscribe(
        (result) => {
          this._router.navigate(['/home']);
        }, (error) => {
          console.log(error);
        }
      )
  }

}
