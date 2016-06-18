@extends('master', ['pagetitle' => 'Home / Contact'])

@section('title')
        <h3>Contact</h3>
@endsection

@section('content')
        <div class="visible-xs">
                <address>
                        <strong>Wiringa Technische Groothandel</strong><br />
                        Bovenstreek 1<br />
                        9731 DH Groningen<br />
                        <abbr title="Telefoon">T:</abbr> <a href="tel:0505445566">050-5445566</a><br />
                        <abbr title="Fax">F:</abbr> 050-5445565<br />
                        <abbr title="E-Mail">E:</abbr> <a href="mailto:verkoop@wiringa.nl">verkoop@wiringa.nl</a><br />
                        <hr />
                        <strong>Openingstijden</strong><br />
                        Maandag t/m Vrijdag<br />
                        Balie: 07:30 - 17:00<br />
                        Telefoon: 08:00 - 17:00<br />
                </address>
        </div>

        <div class="hidden-xs">
                <table class="table table-striped table-hover">
                        <tbody>
                                <tr>
                                        <td><b>Telefoon:</b></td>
                                        <td><a href="tel:0505445566">050-5445566</a></td>
                                        <td>08:00 - 17:00</td>
                                </tr>
                                <tr>
                                        <td><b>Balie:</b></td>
                                        <td>Maandag t/m Vrijdag</td>
                                        <td>07:30 - 17:00</td>
                                </tr>
                                <tr>
                                        <td><b>Fax:</b></td>
                                        <td>050-5445565</td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td><b>E-Mail:</b></td>
                                        <td><a href="mailto:verkoop@wiringa.nl">verkoop@wiringa.nl</a></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td><b>Adres:</b></td>
                                        <td>Bovenstreek 1</td>
                                        <td>9731 DH Groningen</td>
                                </tr>
                        </tbody>
                </table>

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2387.994500319521!2d6.590588013491823!3d53.23587489278383!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c9d29bd46ee021%3A0x9589c80e5b3dd396!2sBovenstreek+1%2C+9731+DH+Groningen!5e0!3m2!1snl!2snl!4v1389359520881" style="width: 100%;" seamless="" frameborder="0" height="600"></iframe>
        </div>
@endsection
